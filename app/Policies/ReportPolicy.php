<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * Any authenticated user can file a report.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only admins can view the full reports list.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Only the original reporter can view their own report (for "my reports"),
     * or an admin can view any report.
     */
    public function view(User $user, Report $report): bool
    {
        return $user->is_admin || $user->id === $report->reporter_id;
    }

    /**
     * Only admins can update (review/dismiss) a report.
     */
    public function update(User $user, Report $report): bool
    {
        return $user->is_admin;
    }

    /**
     * Only admins can delete a report.
     */
    public function delete(User $user, Report $report): bool
    {
        return $user->is_admin;
    }
}
