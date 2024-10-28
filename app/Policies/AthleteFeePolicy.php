<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Athlete;
use App\Enums\Permissions;

class AthleteFeePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Athlete $athlete = null): bool
    {
        return ($user->athlete && $athlete) ? $user->athlete->id == $athlete->id : false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Athlete $athlete): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return false;
    }

    public function subscribe(User $user): bool
    {
        return $user->can(Permissions::HandleSubscriptions);
    }

    public function registerPayment(User $user): bool
    {
        return $user->can(Permissions::HandlePayments);
    }
}
