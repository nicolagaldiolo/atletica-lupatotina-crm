<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Athlete;
use App\Enums\Permissions;

class AthletePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(Permissions::ListAthletes);
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
        return $user->can(Permissions::CreateAthletes);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Athlete $athlete): bool
    {
        return $user->can(Permissions::EditAthletes) || ($user->athlete ? $user->athlete->id == $athlete->id : false);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->can(Permissions::DeleteAthletes);
    }

    public function report(User $user): bool
    {
        return $user->can(Permissions::ReportAthletes);
    }

    public function invite(User $user, Athlete $athlete = null): bool
    {
        return $user->can(Permissions::InviteAthletes) && ($athlete ? !$athlete->user : true);
    }
}
