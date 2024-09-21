<?php

namespace App\Policies;

use App\Enums\FeePermission;
use App\Models\Fee;
use App\Models\User;

class FeePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;//$user->can(FeePermission::ListFees);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Fee $fee): bool
    {
        return false;//$user->can(FeePermission::ViewFees);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;//$user->can(FeePermission::CreateFees);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Fee $fee): bool
    {
        return false;//$user->can(FeePermission::EditFees);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Fee $fee): bool
    {
        return false;//$user->can(FeePermission::DeleteFees);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Fee $fee): bool
    {
        return false;//$user->can(FeePermission::RestoreFees);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Fee $fee): bool
    {
        return false;//$user->can(FeePermission::DeleteFees);
    }
}
