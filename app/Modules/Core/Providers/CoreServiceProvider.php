<?php
namespace App\Modules\Core\Providers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
	/**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('core', function()
        {
            return new \App\Modules\Core\Services\Core;
        });
    }	
}