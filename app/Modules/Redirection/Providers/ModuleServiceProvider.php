<?php

namespace App\Modules\Redirection\Providers;

use Caffeinated\Modules\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'redirection');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'redirection');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'redirection');
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
