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

Route::group([ 'prefix' => 'install' ], function() {
    Route::any('/', [ 'uses' => '\App\Modules\Core\Http\Controllers\InstallController@index', 'as' => 'install.index' ]);
    Route::any('/database', [ 'uses' => '\App\Modules\Core\Http\Controllers\InstallController@database', 'as' => 'install.database' ]);
    Route::any('/me', [ 'uses' => '\App\Modules\Core\Http\Controllers\InstallController@me', 'as' => 'install.me' ]);
    Route::any('/acount', [ 'uses' => '\App\Modules\Core\Http\Controllers\InstallController@account', 'as' => 'install.account' ]);
    Route::any('/finished', [ 'uses' => '\App\Modules\Core\Http\Controllers\InstallController@finished', 'as' => 'install.finished' ]);
});

Route::group([ 'prefix' => 'api' ], function() {
	Route::any('/{module}', [ 'uses' => '\App\Modules\Core\Http\Controllers\ApiController@index' ]);
});

Route::group([ 'prefix' => 'admin', 'middleware' => 'auth.admin' ], function() {
	Route::group([ 'prefix' => 'api' ], function() {
		Route::any('/{module}', [ 'uses' => '\App\Modules\Core\Http\Controllers\AdminApiController@index' ]);
		Route::post('/{module}/post', [ 'uses' => '\App\Modules\Core\Http\Controllers\AdminApiController@post' ]);
		Route::put('/{module}/put/{id}', [ 'uses' => '\App\Modules\Core\Http\Controllers\AdminApiController@put' ]);
		Route::delete('/{module}/delete/{id}', [ 'uses' => '\App\Modules\Core\Http\Controllers\AdminApiController@delete' ]);
	});

	Route::group([ 'prefix' => 'updates', 'middleware' => 'auth.admin' ], function() {
		Route::get('/browse/{module_type?}', [ 'uses' => '\App\Modules\Core\Http\Controllers\AdminUpdatesController@browse', 'as' => 'admin.updates.browse' ]);
		Route::get('/module/view/{id}', [ 'uses' => '\App\Modules\Core\Http\Controllers\AdminUpdatesController@view', 'as' => 'admin.updates.view' ]);

		Route::any('/install/theme/{id}', [ 'uses' => '\App\Modules\Core\Http\Controllers\AdminUpdatesController@installTheme', 'as' => 'admin.updates.install_theme' ]);
		Route::any('/install/plugin/{id}', [ 'uses' => '\App\Modules\Core\Http\Controllers\AdminUpdatesController@installPlugin', 'as' => 'admin.updates.install_plugin' ]);

		Route::any('/update/theme/{id}', [ 'uses' => '\App\Modules\Core\Http\Controllers\AdminUpdatesController@updateTheme', 'as' => 'admin.updates.update_theme' ]);
		Route::any('/update/plugin/{id}', [ 'uses' => '\App\Modules\Core\Http\Controllers\AdminUpdatesController@updatePlugin', 'as' => 'admin.updates.update_plugin' ]);

		Route::any('/upgrade/{type}', [ 'uses' => '\App\Modules\Core\Http\Controllers\AdminUpdatesController@upgrade', 'as' => 'admin.updates.upgrade' ]);

		Route::get('/{module_type?}', [ 'uses' => '\App\Modules\Core\Http\Controllers\AdminUpdatesController@index', 'as' => 'admin.updates.index' ]);
	});

	Route::group([ 'prefix' => 'install' ], function() {

	});

    Route::group([ 'prefix' => 'settings' ], function() {
        Route::any('/', [ 'uses' => '\App\Modules\Core\Http\Controllers\SettingsAdminController@index', 'as' => 'admin.settings.index' ]);
        Route::any('/add', [ 'uses' => '\App\Modules\Core\Http\Controllers\SettingsAdminController@add', 'as' => 'admin.settings.add' ]);
        Route::any('/add/category', [ 'uses' => '\App\Modules\Core\Http\Controllers\SettingsAdminController@addCategory', 'as' => 'admin.settings.add_category' ]);
        Route::any('/simple-save', [ 'uses' => '\App\Modules\Core\Http\Controllers\SettingsAdminController@simpleSave', 'as' => 'admin.settings.simple_save' ]);
    });
});
