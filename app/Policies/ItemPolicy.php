<?php

namespace App\Policies;

use App\Enums\ClaimStatus;
use App\Models\Item;
use App\Models\User;

class ItemPolicy
{
    /**
     * Determine whether the user can view any items.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the item.
     */
    public function view(?User $user, Item $item): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create items.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the item.
     */
    public function update(User $user, Item $item): bool
    {
        return $user->id === $item->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the item.
     */
    public function delete(User $user, Item $item): bool
    {
        return $user->id === $item->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can claim the item.
     */
    public function claim(User $user, Item $item): bool
    {
        // Cannot claim own item
        if ($user->id === $item->user_id) {
            return false;
        }

        // Must be in claimable status
        if (! $item->canBeClaimed()) {
            return false;
        }

        // Cannot submit duplicate pending claim
        $hasPending = $item->claims()
            ->where('user_id', $user->id)
            ->where('status', ClaimStatus::PENDING)
            ->exists();

        return ! $hasPending;
    }
}
