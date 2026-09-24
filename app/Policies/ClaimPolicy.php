<?php

namespace App\Policies;

use App\Enums\ClaimStatus;
use App\Models\Claim;
use App\Models\User;

class ClaimPolicy
{
    /**
     * Determine whether the user can view the claim.
     */
    public function view(User $user, Claim $claim): bool
    {
        return $user->id === $claim->user_id
            || $user->id === $claim->item->user_id
            || $user->is_admin;
    }

    /**
     * Determine whether the user can approve the claim.
     */
    public function approve(User $user, Claim $claim): bool
    {
        return ($user->id === $claim->item->user_id || $user->is_admin)
            && $claim->status === ClaimStatus::PENDING;
    }

    /**
     * Determine whether the user can reject the claim.
     */
    public function reject(User $user, Claim $claim): bool
    {
        return ($user->id === $claim->item->user_id || $user->is_admin)
            && $claim->status === ClaimStatus::PENDING;
    }

    /**
     * Determine whether the claimant can cancel their own claim.
     */
    public function cancel(User $user, Claim $claim): bool
    {
        return $user->id === $claim->user_id
            && $claim->status === ClaimStatus::PENDING;
    }
}
