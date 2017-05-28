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
    Route::group([ 'prefix' => 'files' ], function() {
        Route::get('/', [ 'uses' => '\App\Modules\Files\Http\Controllers\FilesEngineController@index', 'as' => 'admin.files.index' ]);
        Route::any('/add', [ 'uses' => '\App\Modules\Files\Http\Controllers\FilesEngineController@add', 'as' => 'admin.files.add' ]);
        Route::any('/edit/{id}', [ 'uses' => '\App\Modules\Files\Http\Controllers\FilesEngineController@edit', 'as' => 'admin.files.edit' ]);
        Route::get('/delete/{id}', [ 'uses' => '\App\Modules\Files\Http\Controllers\FilesEngineController@delete', 'as' => 'admin.files.delete' ]);
        Route::post('/simple-save', [ 'uses' => '\App\Modules\Files\Http\Controllers\FilesEngineController@simpleSave', 'as' => 'admin.files.simple_save' ]);
    });

    Route::group([ 'prefix' => 'albums' ], function() {
        Route::get('/', [ 'uses' => '\App\Modules\Files\Http\Controllers\AlbumsEngineController@index', 'as' => 'admin.albums.index' ]);
        Route::any('/add', [ 'uses' => '\App\Modules\Files\Http\Controllers\AlbumsEngineController@add', 'as' => 'admin.albums.add' ]);
        Route::any('/edit/{id}', [ 'uses' => '\App\Modules\Files\Http\Controllers\AlbumsEngineController@edit', 'as' => 'admin.albums.edit' ]);
        Route::get('/delete/{id}', [ 'uses' => '\App\Modules\Files\Http\Controllers\AlbumsEngineController@delete', 'as' => 'admin.albums.delete' ]);
        Route::post('/simple-save', [ 'uses' => '\App\Modules\Files\Http\Controllers\AlbumsEngineController@simpleSave', 'as' => 'admin.albums.simple_save' ]);
    });
});

Route::group([ 'prefix' => 'albums' ], function() {
    Route::get('/', [ 'uses' => '\App\Modules\Files\Http\Controllers\AlbumsController@index', 'as' => 'albums.index' ]);
    Route::any('/{slug}', [ 'uses' => '\App\Modules\Files\Http\Controllers\AlbumsController@view', 'as' => 'albums.view' ]);
});
