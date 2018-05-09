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

Route::group([ 'prefix' => 'install', 'namespace' => 'Install' ], function() {
    Route::any('/', [ 'uses' => 'InstallController@index', 'as' => 'install.index' ]);
    Route::any('/database', [ 'uses' => 'InstallController@database', 'as' => 'install.database' ]);
    Route::any('/me', [ 'uses' => 'InstallController@me', 'as' => 'install.me' ]);
    Route::any('/acount', [ 'uses' => 'InstallController@account', 'as' => 'install.account' ]);
    Route::any('/finished', [ 'uses' => 'InstallController@finished', 'as' => 'install.finished' ]);
});

Route::group([ 'prefix' => 'api' ], function() {
	Route::any('/{module}', [ 'uses' => '\App\Modules\Core\Http\Controllers\Admin\ApiController@index', 'as' => 'plugin.core.api.index' ]);
});

Route::group([ 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'role:admin' ], function() {
	Route::group([ 'prefix' => 'api' ], function() {
		Route::any('/{module}', [ 'uses' => 'ApiController@index', 'as' => 'api.index' ]);
		Route::post('/{module}/post', [ 'uses' => 'ApiController@post', 'as' => 'api.post' ]);
		Route::put('/{module}/put/{id}', [ 'uses' => 'ApiController@put', 'as' => 'api.put' ]);
		Route::delete('/{module}/delete/{id}', [ 'uses' => 'ApiController@delete', 'as' => 'api.delete' ]);
	});

	Route::group([ 'prefix' => 'updates' ], function() {
		Route::get('/browse/{module_type?}', [ 'uses' => 'UpdatesController@browse', 'as' => 'admin.updates.browse' ]);
		Route::get('/module/view/{id}', [ 'uses' => 'UpdatesController@view', 'as' => 'admin.updates.view' ]);

		Route::any('/install/theme/{id}', [ 'uses' => 'UpdatesController@installTheme', 'as' => 'admin.updates.install_theme' ]);
		Route::post('/install/themes', [ 'uses' => 'UpdatesController@installThemes', 'as' => 'admin.updates.install_themes' ]);

        Route::post('/install/plugins', [ 'uses' => 'UpdatesController@installPlugins', 'as' => 'admin.updates.install_plugins' ]);
		Route::any('/install/plugin/{id}', [ 'uses' => 'UpdatesController@installPlugin', 'as' => 'admin.updates.install_plugin' ]);

		Route::post('/update/themes', [ 'uses' => 'UpdatesController@updateThemes', 'as' => 'admin.updates.update_themes' ]);
		Route::any('/update/theme/{id}', [ 'uses' => 'UpdatesController@updateTheme', 'as' => 'admin.updates.update_theme' ]);

        Route::any('/update/plugins', [ 'uses' => 'UpdatesController@updatePlugins', 'as' => 'admin.updates.update_plugins' ]);
        Route::any('/update/plugin/{id}', [ 'uses' => 'UpdatesController@updatePlugin', 'as' => 'admin.updates.update_plugin' ]);

		Route::any('/upgrade/{type}', [ 'uses' => 'UpdatesController@upgrade', 'as' => 'admin.updates.upgrade' ]);

		Route::get('/', [ 'uses' => 'UpdatesController@index', 'as' => 'admin.updates.index' ]);
	});

    Route::group([ 'prefix' => 'marketplace' ], function() {
        Route::get('/account', [ 'uses' => 'MarketplaceController@account', 'as' => 'admin.marketplace.account' ])->middleware('auth:api', 'scopes:account,paid-extensions');
        Route::get('/purchase/{id}', [ 'uses' => 'MarketplaceController@purchase', 'as' => 'admin.marketplace.purchase' ])->middleware('auth:api', 'scopes:account,paid-extensions');
    });

    Route::group([ 'prefix' => 'settings' ], function() {
        Route::any('/', [ 'uses' => 'SettingsController@index', 'as' => 'admin.settings.index' ]);
        Route::any('/add', [ 'uses' => 'SettingsController@add', 'as' => 'admin.settings.add' ]);
        Route::any('/add/category', [ 'uses' => 'SettingsController@addCategory', 'as' => 'admin.settings.add_category' ]);
        Route::any('/simple-save', [ 'uses' => 'SettingsController@simpleSave', 'as' => 'admin.settings.simple_save' ]);
    });
});
