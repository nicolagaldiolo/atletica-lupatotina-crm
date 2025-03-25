<?php

namespace App\Policies;

use App\Enums\Permissions;
use App\Models\Race;
use App\Models\User;

class RacePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAnyRace(User $user): bool
    {
        return $user->can(Permissions::ListRaces);
    }

    public function viewAnyTrack(User $user): bool
    {
        return $user->can(Permissions::ListTrack);
    }

    /**
     * Determine whether the user can create models.
     */
    public function createRace(User $user): bool
    {
        return $user->can(Permissions::CreateRaces);
    }

    public function createTrack(User $user): bool
    {
        return $user->can(Permissions::CreateTrack);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateRace(User $user, Race $race): bool
    {
        return $user->can(Permissions::EditRaces);
    }

    public function updateTrack(User $user, Race $race): bool
    {
        return $user->can(Permissions::EditTrack);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteRace(User $user, Race $race): bool
    {
        return $user->can(Permissions::DeleteRaces);
    }

    public function deleteTrack(User $user, Race $race): bool
    {
        return $user->can(Permissions::DeleteTrack);
    }

    public function reportRace(User $user): bool
    {
        return $user->can(Permissions::ReportRaces);
    }

    public function reportTrack(User $user): bool
    {
        return $user->can(Permissions::ReportTrack);
    }
}
