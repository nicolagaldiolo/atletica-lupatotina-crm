<?php

namespace App\Policies;

use App\Enums\Permissions;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(Permissions::ListUsers);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(Permissions::CreateUsers);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return false; //$user->can(Permissions::EditUsers);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return false; //$user->can(Permissions::DeleteUsers);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function block(User $user, User $model): bool
    {
        return false; //$user->can(Permissions::BlockUsers);
    }
}
