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

Route::group(['prefix' => 'admin'], function () {
    Route::group([ 'prefix' => 'themes', 'namespace' => 'Admin', 'middleware' => 'role:admin' ], function() {
        Route::get('/', [ 'uses' => 'ThemesController@index', 'as' => 'admin.themes.index' ]);
        Route::any('/add', [ 'uses' => 'ThemesController@add', 'as' => 'admin.themes.add' ]);
        Route::any('/build/{step?}', [ 'uses' => 'ThemesController@build', 'as' => 'admin.themes.build' ]);
        Route::any('/edit/{id}', [ 'uses' => 'ThemesController@edit', 'as' => 'admin.themes.edit' ]);
        Route::any('/edit_templates/{id}', [ 'uses' => 'ThemesController@edit_templates', 'as' => 'admin.themes.edit_templates' ]);
        Route::any('/edit_template/{id}/{path}', [ 'uses' => 'ThemesController@edit_template', 'as' => 'admin.themes.edit_template' ]);
        Route::get('/delete/{id}', [ 'uses' => 'ThemesController@delete', 'as' => 'admin.themes.delete' ]);
        Route::any('/status/{id}', [ 'uses' => 'ThemesController@status', 'as' => 'admin.themes.status' ]);
        Route::any('/activate/{slug}', [ 'uses' => 'ThemesController@activate', 'as' => 'admin.themes.activate' ]);
        Route::post('/simple-save', [ 'uses' => 'ThemesController@simpleSave', 'as' => 'admin.themes.simple_save' ]);
    });
});
