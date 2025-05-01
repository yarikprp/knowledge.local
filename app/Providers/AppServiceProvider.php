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
        Gate::define('role-access', function ($user, $roleId) {
            return $user->roles->contains('id', $roleId);
        });

        Gate::define('is-admin', function ($user) {
            return $user->id == 1;
        });
    }
}
