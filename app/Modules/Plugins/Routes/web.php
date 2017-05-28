<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['prefix' => 'admin', 'middleware' => 'auth.admin'], function () {
    Route::group([ 'prefix' => 'plugins' ], function() {
        Route::get('/', [ 'uses' => '\App\Modules\Plugins\Http\Controllers\PluginsEngineController@index', 'as' => 'admin.plugins.index' ]);
        Route::any('/install/{slug}', [ 'uses' => '\App\Modules\Plugins\Http\Controllers\PluginsEngineController@install', 'as' => 'admin.plugins.install' ]);
        Route::any('/uninstall/{slug}', [ 'uses' => '\App\Modules\Plugins\Http\Controllers\PluginsEngineController@uninstall', 'as' => 'admin.plugins.uninstall' ]);
    });
});
