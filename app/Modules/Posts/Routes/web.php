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
Route::get('/', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PagesController@home', 'as' => 'home' ]);
Route::get('/pages/{slug}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PagesController@view', 'as' => 'pages.view' ]);

Route::group(['prefix' => 'posts'], function () {
	Route::get('/{slug}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PostsController@view', 'as' => 'posts.view' ]);
});

Route::get('/tag/{slug}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\TagsController@view', 'as' => 'tags.view' ]);

Route::get('/category/{slug}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\CategoriesController@view', 'as' => 'categories.view' ]);

Route::group(['prefix' => 'admin', 'middleware' => 'role:admin'], function () {
	Route::get('/', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PagesEngineController@dashboard', 'as' => 'admin.dashboard' ]);

	// pages
    Route::group([ 'prefix' => 'pages' ], function() {
        Route::get('/', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PagesEngineController@index', 'as' => 'admin.pages.index' ]);
        Route::any('/add', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PagesEngineController@add', 'as' => 'admin.pages.add' ]);
        Route::any('/edit/{id}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PagesEngineController@edit', 'as' => 'admin.pages.edit' ]);
        Route::get('/delete/{id}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PagesEngineController@delete', 'as' => 'admin.pages.delete' ]);
        Route::any('/order', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PagesEngineController@order', 'as' => 'admin.pages.order' ]);
        Route::post('/simple-save', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PagesEngineController@simpleSave', 'as' => 'admin.pages.simple_save' ]);
    });

    // tags
    Route::group([ 'prefix' => 'tags' ], function() {
        Route::get('/', [ 'uses' => '\App\Modules\Posts\Http\Controllers\TagsEngineController@index', 'as' => 'admin.tags.index' ]);
        Route::any('/add', [ 'uses' => '\App\Modules\Posts\Http\Controllers\TagsEngineController@add', 'as' => 'admin.tags.add' ]);
        Route::any('/edit/{id}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\TagsEngineController@edit', 'as' => 'admin.tags.edit' ]);
        Route::get('/delete/{id}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\TagsEngineController@delete', 'as' => 'admin.tags.delete' ]);
        Route::post('/simple-save', [ 'uses' => '\App\Modules\Posts\Http\Controllers\TagsEngineController@simpleSave', 'as' => 'admin.tags.simple_save' ]);
    });

    // fields
    Route::group([ 'prefix' => 'fields' ], function() {
	    Route::get('/', [ 'uses' => '\App\Modules\Posts\Http\Controllers\FieldsEngineController@index', 'as' => 'admin.fields.index' ]);
	    Route::any('/add', [ 'uses' => '\App\Modules\Posts\Http\Controllers\FieldsEngineController@add', 'as' => 'admin.fields.add' ]);
	    Route::any('/edit/{id}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\FieldsEngineController@edit', 'as' => 'admin.fields.edit' ]);
	    Route::get('/delete/{id}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\FieldsEngineController@delete', 'as' => 'admin.fields.delete' ]);
		Route::any('/order', [ 'uses' => '\App\Modules\Posts\Http\Controllers\FieldsEngineController@order', 'as' => 'admin.fields.order' ]);
		Route::post('/simple-save', [ 'uses' => '\App\Modules\Posts\Http\Controllers\FieldsEngineController@simpleSave', 'as' => 'admin.fields.simple_save' ]);
    });

    // posts
    Route::group([ 'prefix' => 'posts' ], function() {
        Route::get('/', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PostsEngineController@index', 'as' => 'admin.posts.index' ]);
        Route::any('/add/{category_id}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PostsEngineController@add', 'as' => 'admin.posts.add' ]);
        Route::any('/edit/{id}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PostsEngineController@edit', 'as' => 'admin.posts.edit' ]);
        Route::get('/delete/{id}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PostsEngineController@delete', 'as' => 'admin.posts.delete' ]);
        Route::get('/status/{id}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PostsEngineController@status', 'as' => 'admin.posts.status' ]);
        Route::get('/restore/{id}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PostsEngineController@restore', 'as' => 'admin.posts.restore' ]);
        Route::post('/simple-save', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PostsEngineController@simpleSave', 'as' => 'admin.posts.simple_save' ]);
    });

    // categories
    Route::group([ 'prefix' => 'categories' ], function() {
	    Route::get('/', [ 'uses' => '\App\Modules\Posts\Http\Controllers\CategoriesEngineController@index', 'as' => 'admin.categories.index' ]);
	    Route::any('/add', [ 'uses' => '\App\Modules\Posts\Http\Controllers\CategoriesEngineController@add', 'as' => 'admin.categories.add' ]);
	    Route::any('/edit/{id}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\CategoriesEngineController@edit', 'as' => 'admin.categories.edit' ]);
	    Route::get('/delete/{id}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\CategoriesEngineController@delete', 'as' => 'admin.categories.delete' ]);
	    Route::any('/order', [ 'uses' => '\App\Modules\Posts\Http\Controllers\CategoriesEngineController@order', 'as' => 'admin.categories.order' ]);
	    Route::post('/simple-save', [ 'uses' => '\App\Modules\Posts\Http\Controllers\CategoriesEngineController@simpleSave', 'as' => 'admin.categories.simple_save' ]);
    });
});
