<?php

namespace App\Modules\Sitemap\Providers;

use Illuminate\Foundation\AliasLoader;
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
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'sitemap');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'sitemap');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'sitemap');
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        // register route SP
        $this->app->register(RouteServiceProvider::class);

        // register sitemap facade
        $loader = AliasLoader::getInstance();
        $loader->alias('Sitemap', \Watson\Sitemap\Facades\Sitemap::class);

        // register sitemap SP
        $this->app->register('\Watson\Sitemap\SitemapServiceProvider');
    }
}
