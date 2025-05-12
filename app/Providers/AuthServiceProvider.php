<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('admin-nav', function ($user) {
            return in_array($user->role_id, [0, 1]);
        });

        Gate::define('business-nav', function ($user) {
            return $user->role_id == 2;
        });

        Gate::define('customer-nav', function ($user) {
            return $user->role_id == 3;
        });
    }
}
