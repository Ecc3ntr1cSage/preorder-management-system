<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Blade;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerComponent('dashboard-nav');
        $this->registerComponent('dashboard-panel');
        $this->registerComponent('admin-panel');
        $this->registerComponent('admin-table');
        $this->registerComponent('flash');
        $this->registerComponent('filepond');
        $this->registerComponent('glowing-card');
        $this->registerComponent('gradient-card');
        $this->registerComponent('textarea');
        $this->registerComponent('loading-screen');
        $this->registerComponent('button-custom');
        $this->registerComponent('rich-text');
    }
    protected function registerComponent(string $component)
    {
        Blade::component('jetstream::components.'.$component);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);

        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
