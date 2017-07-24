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
    Route::group([ 'prefix' => 'themes', 'middleware' => 'role:admin' ], function() {
        Route::get('/', [ 'uses' => '\App\Modules\Themes\Http\Controllers\ThemesEngineController@index', 'as' => 'admin.themes.index' ]);
        Route::any('/add', [ 'uses' => '\App\Modules\Themes\Http\Controllers\ThemesEngineController@add', 'as' => 'admin.themes.add' ]);
        Route::any('/build/{step?}', [ 'uses' => '\App\Modules\Themes\Http\Controllers\ThemesEngineController@build', 'as' => 'admin.themes.build' ]);
        Route::any('/edit/{id}', [ 'uses' => '\App\Modules\Themes\Http\Controllers\ThemesEngineController@edit', 'as' => 'admin.themes.edit' ]);
        Route::any('/edit_templates/{id}', [ 'uses' => '\App\Modules\Themes\Http\Controllers\ThemesEngineController@edit_templates', 'as' => 'admin.themes.edit_templates' ]);
        Route::any('/edit_template/{id}/{path}', [ 'uses' => '\App\Modules\Themes\Http\Controllers\ThemesEngineController@edit_template', 'as' => 'admin.themes.edit_template' ]);
        Route::get('/delete/{id}', [ 'uses' => '\App\Modules\Themes\Http\Controllers\ThemesEngineController@delete', 'as' => 'admin.themes.delete' ]);
        Route::any('/status/{id}', [ 'uses' => '\App\Modules\Themes\Http\Controllers\ThemesEngineController@status', 'as' => 'admin.themes.status' ]);
        Route::any('/activate/{slug}', [ 'uses' => '\App\Modules\Themes\Http\Controllers\ThemesEngineController@activate', 'as' => 'admin.themes.activate' ]);
        Route::post('/simple-save', [ 'uses' => '\App\Modules\Themes\Http\Controllers\ThemesEngineController@simpleSave', 'as' => 'admin.themes.simple_save' ]);
    });
});
