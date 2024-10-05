<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Athlete;
use App\Enums\Permissions;
use App\Models\Certificate;
use App\Models\Voucher;

class VoucherPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(Permissions::ListVouchers);
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
        return $user->can(Permissions::CreateVouchers);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Voucher $voucher): bool
    {
        return $user->can(Permissions::EditVouchers) && !$voucher->used_at;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Voucher $voucher): bool
    {
        return $user->can(Permissions::DeleteVouchers) && !$voucher->used_at;
    }
}
