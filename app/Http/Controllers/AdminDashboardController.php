<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Claim;
use App\Models\Report;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display admin overview dashboard with key statistics.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_items' => Item::count(),
            'active_items' => Item::where('status', 'active')->count(),
            'total_claims' => Claim::count(),
            'pending_claims' => Claim::where('status', 'pending')->count(),
            'unresolved_reports' => Report::where('status', 'pending')->count(),
        ];

        $recent_items = Item::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        $recent_reports = Report::with(['reporter','reportable'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_items', 'recent_reports'));
    }

    /**
     * Display user management list with trust metrics.
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->withCount(['items', 'claims'])
            ->latest()
            ->paginate(15);

        return view('admin.users', compact('users'));
    }

    /**
     * Toggle user admin privileges.
     */
    public function toggleAdmin(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot modify your own admin status.');
        }

        $user->update(['is_admin' => !$user->is_admin]);

        return back()->with('status', "User admin privileges updated successfully.");
    }

    /**
     * Delete user and their associated data.
     */
    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account from admin dashboard.');
        }

        $user->delete();

        return back()->with('status', 'User account deleted successfully.');
    }

    /**
     * Display all items for moderation.
     */
    public function items(Request $request)
    {
        $query = Item::with(['user', 'category']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        $items = $query->latest()->paginate(15);

        return view('admin.items', compact('items'));
    }

    /**
     * Delete/remove an item from the platform (Moderation).
     */
    public function deleteItem(Item $item)
    {
        $item->delete();

        return back()->with('status', 'Item removed successfully.');
    }

    /**
     * Overview of all claims across the system.
     */
    public function claims()
    {
        $claims = Claim::with(['item', 'user'])
            ->latest()
            ->paginate(15);

        return view('admin.claims', compact('claims'));
    }
}