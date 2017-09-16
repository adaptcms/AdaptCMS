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

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'role:admin'], function () {
    Route::group([ 'prefix' => 'plugins' ], function() {
        Route::get('/', [ 'uses' => 'PluginsController@index', 'as' => 'admin.plugins.index' ]);
        Route::any('/install/{slug}', [ 'uses' => 'PluginsController@install', 'as' => 'admin.plugins.install' ]);
        Route::any('/uninstall/{slug}', [ 'uses' => 'PluginsController@uninstall', 'as' => 'admin.plugins.uninstall' ]);
    });
});
