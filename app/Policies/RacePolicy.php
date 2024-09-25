<?php

namespace App\Policies;

use App\Enums\Permissions;
use App\Enums\RacePermission;
use App\Models\Race;
use App\Models\User;

class RacePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;//$user->can(RacePermission::ListRaces);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Race $race): bool
    {
        return false;//$user->can(RacePermission::ViewRaces);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;//$user->can(RacePermission::CreateRaces);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Race $race): bool
    {
        return false;//$user->can(RacePermission::EditRaces);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Race $race): bool
    {
        return false;//$user->can(RacePermission::DeleteRaces);
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
