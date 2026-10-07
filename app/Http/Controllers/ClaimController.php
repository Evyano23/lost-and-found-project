<?php

namespace App\Http\Controllers;

use App\Enums\ClaimStatus;
use App\Enums\ItemStatus;
use App\Http\Requests\StoreClaimRequest;
use App\Mail\ClaimApproved;
use App\Mail\ClaimRejected;
use App\Mail\ClaimSubmitted;
use App\Models\Claim;
use App\Models\Item;
use App\Notifications\ClaimApprovedNotification;
use App\Notifications\ClaimRejectedNotification;
use App\Notifications\ClaimSubmittedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ClaimController extends Controller
{
    /**
     * Display all claims submitted by the authenticated user.
     */
    public function myClaims(Request $request): View
    {
        $claims = $request->user()
            ->claims()
            ->with(['item.category', 'item.user', 'verificationAnswers.question'])
            ->latest()
            ->paginate(10);

        return view('claims.my-claims', compact('claims'));
    }

    /**
     * Display all claims received for a specific item (for item reporter review).
     */
    public function itemClaims(Item $item): View
    {
        Gate::authorize('update', $item);

        $claims = $item->claims()
            ->with(['user', 'verificationAnswers.question'])
            ->latest()
            ->paginate(10);

        return view('claims.item-claims', compact('item', 'claims'));
    }

    /**
     * Store a newly submitted claim for an item.
     */
    public function store(StoreClaimRequest $request, Item $item): RedirectResponse
    {
        $validated = $request->validated();

        $claim = DB::transaction(function () use ($request, $item, $validated) {
            $proofImagePath = null;
            if ($request->hasFile('proof_image')) {
                $proofImagePath = $request->file('proof_image')->store('claims', 'public');
            }

            $newClaim = $item->claims()->create([
                'user_id' => $request->user()->id,
                'proof_description' => $validated['proof_description'],
                'proof_image' => $proofImagePath,
                'status' => ClaimStatus::PENDING,
                'contact_phone' => $validated['contact_phone'] ?? null,
                'contact_email' => $validated['contact_email'] ?? null,
            ]);

            // Save verification answers if provided
            if (! empty($validated['verification_answers'])) {
                foreach ($validated['verification_answers'] as $questionId => $answerText) {
                    if (! empty(trim($answerText ?? ''))) {
                        $newClaim->verificationAnswers()->create([
                            'verification_question_id' => $questionId,
                            'answer' => trim($answerText),
                        ]);
                    }
                }
            }

            // Transition item status to PENDING_CLAIM if it was ACTIVE
            if ($item->status === ItemStatus::ACTIVE) {
                $item->update(['status' => ItemStatus::PENDING_CLAIM]);
            }

            return $newClaim;
        });

        if ($item->user) {
            Mail::to($item->user)->send(new ClaimSubmitted($claim));
            $item->user->notify(new ClaimSubmittedNotification($claim));
        }

        return redirect()->route('items.show', $item)
            ->with('success', 'Your ownership claim has been submitted to the finder for review!');
    }

    /**
     * Approve a claim and mark the item as claimed.
     */
    public function approve(Request $request, Claim $claim): RedirectResponse
    {
        Gate::authorize('approve', $claim);

        $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $claim->approve($request->filled('notes') ? $request->string('notes')->value() : null);

        if ($claim->user) {
            Mail::to($claim->user)->send(new ClaimApproved($claim));
            $claim->user->notify(new ClaimApprovedNotification($claim));
        }

        return back()->with('success', 'Claim approved! The item has been marked as claimed.');
    }

    /**
     * Reject a claim and revert item status if no other pending claims exist.
     */
    public function reject(Request $request, Claim $claim): RedirectResponse
    {
        Gate::authorize('reject', $claim);

        $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $claim->reject($request->filled('notes') ? $request->string('notes')->value() : null);

        if ($claim->user) {
            Mail::to($claim->user)->send(new ClaimRejected($claim));
            $claim->user->notify(new ClaimRejectedNotification($claim));
        }

        return back()->with('success', 'Claim rejected.');
    }

    /**
     * Cancel a submitted claim (withdrawn by claimant) and revert item status if needed.
     */
    public function cancel(Claim $claim): RedirectResponse
    {
        Gate::authorize('cancel', $claim);

        $claim->cancel();

        return back()->with('success', 'Your claim has been withdrawn.');
    }
}
