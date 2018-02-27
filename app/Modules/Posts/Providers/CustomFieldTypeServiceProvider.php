<?php
namespace App\Modules\Posts\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class CustomFieldTypeServiceProvider extends ServiceProvider
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
        App::bind('customFieldType', function () {
            return new \App\Modules\Posts\Services\CustomFieldType;
        });
    }
}