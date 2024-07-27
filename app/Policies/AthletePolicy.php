<?php

namespace App\Policies;

use App\Enums\AthletePermission;
use App\Models\Athlete;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AthletePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(AthletePermission::ListAthletes);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can(AthletePermission::ViewAthletes);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(AthletePermission::CreateAthletes);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Athlete $athlete): bool
    {
        return $user->can(AthletePermission::EditAthletes) || $user->athlete->id == $athlete->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->can(AthletePermission::DeleteAthletes);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return $user->can(AthletePermission::RestoreAthletes);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        return $user->can(AthletePermission::DeleteAthletes);
    }
}
