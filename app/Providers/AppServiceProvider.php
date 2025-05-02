<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            if ($user->id == 1) {
                return true;
            }
        });

        Gate::define('role-access', function ($user, $roleId) {
            return $user->roles->contains('id', $roleId);
        });

        Gate::define('is-admin', function ($user) {
            return $user->id == 1;
        });

        Gate::define('is-mentor', function ($user) {
            return $user->id == 2;
        });

        Gate::define('is-employee', function ($user) {
            return $user->id == 3;
        });
    }
}
