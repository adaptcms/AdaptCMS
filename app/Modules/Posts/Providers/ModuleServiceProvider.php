<?php

namespace App\Modules\Posts\Providers;

use Caffeinated\Modules\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        if (Schema::hasTable('field_types')) {
            CustomFieldType::sync();
        }
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
