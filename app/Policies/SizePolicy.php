<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\Permissions;
use App\Models\Size;

class SizePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(Permissions::ListArticles);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Size $size): bool
    {
        return $user->can(Permissions::ViewArticles);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(Permissions::CreateArticles);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Size $size): bool
    {
        return $user->can(Permissions::EditArticles);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Size $size): bool
    {
        return $user->can(Permissions::DeleteArticles);
    }
}
