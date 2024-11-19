<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Athlete;
use App\Enums\Permissions;
use App\Models\Certificate;

class CertificatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Athlete $athlete = null): bool
    {
        return $user->can(Permissions::ListCertificates) || (($user->athlete && $athlete) ? $user->athlete->id == $athlete->id : false);
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
    public function create(User $user, Athlete $athlete): bool
    {
        return $user->can(Permissions::CreateCertificates);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Certificate $certificate): bool
    {
        return $user->can(Permissions::EditCertificates);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Certificate $certificate): bool
    {
        return $user->can(Permissions::DeleteCertificates) && !$certificate->is_current;
    }
}
