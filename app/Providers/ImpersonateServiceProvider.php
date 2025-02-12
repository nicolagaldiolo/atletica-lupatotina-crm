<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ImpersonateServiceProvider extends ServiceProvider {

    public function boot()
    {
        view::composer('backend.includes.header', function ($view) {
            if (Auth::user()->canImpersonate()) {
                $otherUsers =  User::where('id', '<>', Auth::user()->id);

                $impersonator = app('impersonate')->getImpersonatorId();
                if (! $impersonator) {
                    $otherUsers = $otherUsers->where('id', '<>', $impersonator);
                }

                return $view->with('otherUsers', $otherUsers->orderBy('name')->get());
            }
        });

    }
}
