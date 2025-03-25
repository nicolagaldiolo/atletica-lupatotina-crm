<?php

namespace App\Policies;

use App\Enums\FeePermission;
use App\Enums\Permissions;
use App\Models\Fee;
use App\Models\User;

class FeePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAnyRace(User $user): bool
    {
        return $user->can(Permissions::EditRaces);
    }

    public function viewAnyTrack(User $user): bool
    {
        return $user->can(Permissions::EditTrack);
    }

    /**
     * Determine whether the user can create models.
     */
    public function createRace(User $user): bool
    {
        return $user->can(Permissions::EditRaces);
    }

    public function createTrack(User $user): bool
    {
        return $user->can(Permissions::EditTrack);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateRace(User $user, Fee $fee): bool
    {
        return $user->can(Permissions::EditRaces);
    }

    public function updateTrack(User $user, Fee $fee): bool
    {
        return $user->can(Permissions::EditTrack);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteRace(User $user, Fee $fee): bool
    {
        return $user->can(Permissions::EditRaces);
    }

    public function deleteTrack(User $user, Fee $fee): bool
    {
        return $user->can(Permissions::EditTrack);
    }
}
