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
    Route::group([ 'prefix' => 'files' ], function() {
        Route::get('/', [ 'uses' => 'FilesController@index', 'as' => 'admin.files.index' ]);
        Route::any('/add', [ 'uses' => 'FilesController@add', 'as' => 'admin.files.add' ]);
        Route::any('/edit/{id}', [ 'uses' => 'FilesController@edit', 'as' => 'admin.files.edit' ]);
        Route::get('/delete/{id}', [ 'uses' => 'FilesController@delete', 'as' => 'admin.files.delete' ]);
        Route::post('/simple-save', [ 'uses' => 'FilesController@simpleSave', 'as' => 'admin.files.simple_save' ]);
    });

    Route::group([ 'prefix' => 'albums' ], function() {
        Route::get('/', [ 'uses' => 'AlbumsController@index', 'as' => 'admin.albums.index' ]);
        Route::any('/add', [ 'uses' => 'AlbumsController@add', 'as' => 'admin.albums.add' ]);
        Route::any('/edit/{id}', [ 'uses' => 'AlbumsController@edit', 'as' => 'admin.albums.edit' ]);
        Route::get('/delete/{id}', [ 'uses' => 'AlbumsController@delete', 'as' => 'admin.albums.delete' ]);
        Route::post('/simple-save', [ 'uses' => 'AlbumsController@simpleSave', 'as' => 'admin.albums.simple_save' ]);
    });
});

Route::group([ 'prefix' => 'albums' ], function() {
    Route::get('/', [ 'uses' => '\App\Modules\Files\Http\Controllers\AlbumsController@index', 'as' => 'albums.index' ]);
    Route::any('/{slug}', [ 'uses' => '\App\Modules\Files\Http\Controllers\AlbumsController@view', 'as' => 'albums.view' ]);
});
