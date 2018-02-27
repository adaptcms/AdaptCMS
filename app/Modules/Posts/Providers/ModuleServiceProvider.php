<?php

namespace App\Modules\Posts\Providers;

use Caffeinated\Modules\Support\ServiceProvider;

use CustomFieldType;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'posts');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'posts');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'posts');

        // load in field types
        CustomFieldType::sync();
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
