<?php

namespace App\Policies;

use App\Enums\OrderStatus;
use App\Models\User;
use App\Enums\Permissions;
use App\Models\Article;
use App\Models\Athlete;
use App\Models\Order;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Athlete $athlete): bool
    {
        return $user->can(Permissions::ListOrders) || ($athlete ? $user->athlete->id == $athlete->id : false);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order, Athlete $athlete): bool
    {
        return $user->can(Permissions::ViewOrders) || ($athlete ? $user->athlete->id == $athlete->id : false);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Athlete $athlete): bool
    {
        return ($user->can(Permissions::CreateOrders) || 
            ($athlete ? $user->athlete->id == $athlete->id : false)) && isOrderEnable();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order, Athlete $athlete): bool
    {
        return ($user->can(Permissions::EditOrders) || 
            (
                ($athlete ? $user->athlete->id == $athlete->id : false) &&
                ($order->season->is_open ?? false) &&
                $order->status == OrderStatus::Pending
            )
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order, Athlete $athlete): bool
    {
        return ($user->can(Permissions::DeleteOrders) || 
            (
                ($athlete ? $user->athlete->id == $athlete->id : false) &&
                ($order->season->is_open ?? false) &&
                $order->status == OrderStatus::Pending
            )
        );
    }

    public function registerPayment(User $user): bool
    {
        return $user->can(Permissions::HandlePaymentsOrders);
    }

    public function deductPayment(User $user): bool
    {
        return $user->can(Permissions::DeductPaymentsOrders);
    }
}
