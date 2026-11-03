<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
        // Use a neutral public path so browser privacy filters do not block
        // Livewire's default /livewire-* asset and update endpoints.
        Livewire::setScriptRoute(function ($handle) {
            return Route::get('/_vhb-cms/filament', $handle);
        });

        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/admin/cms-assets/update', $handle);
        });
    }
}
