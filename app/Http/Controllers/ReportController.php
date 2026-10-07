<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Mail\ReportSubmitted;
use App\Models\Claim;
use App\Models\Item;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Show the admin dashboard listing all reports.
     * Only admins may access this.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Report::class);

        $query = Report::with(['reporter', 'reportable'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->value());
        }

        $reports = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Report::count(),
            'pending' => Report::where('status', 'pending')->count(),
            'reviewed' => Report::where('status', 'reviewed')->count(),
            'dismissed' => Report::where('status', 'dismissed')->count(),
        ];

        return view('reports.admin.index', compact('reports', 'stats'));
    }

    /**
     * Store a new report against an Item or Claim.
     * Route: POST /items/{item}/reports  or  POST /claims/{claim}/reports
     */
    public function store(StoreReportRequest $request, string $type, int $id): RedirectResponse
    {
        Gate::authorize('create', Report::class);

        // Resolve the reportable model from the route segment
        $reportable = match ($type) {
            'item' => Item::findOrFail($id),
            'claim' => Claim::findOrFail($id),
            default => abort(404),
        };

        // Prevent duplicate pending reports from the same user on the same model
        $alreadyReported = Report::where('reportable_type', $type)
            ->where('reportable_id', $id)
            ->where('reporter_id', $request->user()->id)
            ->where('status', ReportStatus::PENDING)
            ->exists();

        if ($alreadyReported) {
            return back()->with('error', 'You have already filed a pending report for this item.');
        }

        $report = DB::transaction(function () use ($request, $reportable) {
            return Report::create([
                'reportable_type' => array_search(get_class($reportable), Relation::morphMap()) ?: get_class($reportable),
                'reportable_id' => $reportable->id,
                'reporter_id' => $request->user()->id,
                'type' => $request->validated()['type'],
                'reason' => $request->validated()['reason'] ?? null,
                'status' => ReportStatus::PENDING,
            ]);
        });

        // Notify admins — synchronous for now.
        // TODO: swap to Mail::to()->queue() once queue worker is confirmed running.
        $admins = User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            Mail::to($admin)->send(new ReportSubmitted($report));
        }

        return back()->with('success', 'Thank you — your report has been submitted and will be reviewed by our team.');
    }

    /**
     * Show a single report (admin or reporter view).
     */
    public function show(Report $report): View
    {
        Gate::authorize('view', $report);

        $report->load(['reporter', 'reportable']);

        return view('reports.show', compact('report'));
    }

    /**
     * Admin action: update a report's status and validity.
     * Increments reports_against_count on the *author* of the reported content
     * ONLY when status becomes REVIEWED and is_valid is true.
     */
    public function update(UpdateReportRequest $request, Report $report): RedirectResponse
    {
        Gate::authorize('update', $report);

        $validated = $request->validated();

        DB::transaction(function () use ($report, $validated) {
            $report->update([
                'status' => $validated['status'],
                'is_valid' => $validated['is_valid'] ?? null,
            ]);

            // SAFETY: increment only when REVIEWED + is_valid = true.
            // Target is the *author* of the reported Item or Claim, never the reporter.
            if ($report->status === ReportStatus::REVIEWED && $report->is_valid === true) {
                $reportedModel = $report->reportable; // Item or Claim instance

                // Both Item and Claim have a user() BelongsTo relationship to their author.
                if ($reportedModel && method_exists($reportedModel, 'user') && $reportedModel->user) {
                    $reportedModel->user->increment('reports_against_count');
                }
            }
        });

        return back()->with('success', 'Report updated successfully.');
    }

    /**
     * Show the authenticated user's own submitted reports ("My Reports").
     */
    public function myReports(Request $request): View
    {
        $reports = $request->user()
            ->reports()
            ->with('reportable')
            ->latest()
            ->paginate(15);

        return view('reports.my-reports', compact('reports'));
    }
}
