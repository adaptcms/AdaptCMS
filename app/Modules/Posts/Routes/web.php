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

Route::get('/archive/{year}/{month}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\PostsController@archive', 'as' => 'posts.archive' ]);

Route::get('/tag/{slug}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\TagsController@view', 'as' => 'tags.view' ]);

Route::get('/category/{slug}', [ 'uses' => '\App\Modules\Posts\Http\Controllers\CategoriesController@view', 'as' => 'categories.view' ]);

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'role:admin'], function () {
	Route::get('/', [ 'uses' => 'PagesController@dashboard', 'as' => 'admin.dashboard' ]);

	// pages
    Route::group([ 'prefix' => 'pages' ], function() {
        Route::get('/', [ 'uses' => 'PagesController@index', 'as' => 'admin.pages.index' ]);
        Route::any('/add', [ 'uses' => 'PagesController@add', 'as' => 'admin.pages.add' ]);
        Route::any('/edit/{id}', [ 'uses' => 'PagesController@edit', 'as' => 'admin.pages.edit' ]);
        Route::get('/delete/{id}', [ 'uses' => 'PagesController@delete', 'as' => 'admin.pages.delete' ]);
        Route::any('/order', [ 'uses' => 'PagesController@order', 'as' => 'admin.pages.order' ]);
        Route::post('/simple-save', [ 'uses' => 'PagesController@simpleSave', 'as' => 'admin.pages.simple_save' ]);
    });

    // tags
    Route::group([ 'prefix' => 'tags' ], function() {
        Route::get('/', [ 'uses' => 'TagsController@index', 'as' => 'admin.tags.index' ]);
        Route::any('/add', [ 'uses' => 'TagsController@add', 'as' => 'admin.tags.add' ]);
        Route::any('/edit/{id}', [ 'uses' => 'TagsController@edit', 'as' => 'admin.tags.edit' ]);
        Route::get('/delete/{id}', [ 'uses' => 'TagsController@delete', 'as' => 'admin.tags.delete' ]);
        Route::post('/simple-save', [ 'uses' => 'TagsController@simpleSave', 'as' => 'admin.tags.simple_save' ]);
    });

    // fields
    Route::group([ 'prefix' => 'fields' ], function() {
	    Route::get('/', [ 'uses' => 'FieldsController@index', 'as' => 'admin.fields.index' ]);
	    Route::any('/add', [ 'uses' => 'FieldsController@add', 'as' => 'admin.fields.add' ]);
	    Route::any('/edit/{id}', [ 'uses' => 'FieldsController@edit', 'as' => 'admin.fields.edit' ]);
	    Route::get('/delete/{id}', [ 'uses' => 'FieldsController@delete', 'as' => 'admin.fields.delete' ]);
		Route::any('/order', [ 'uses' => 'FieldsController@order', 'as' => 'admin.fields.order' ]);
		Route::post('/simple-save', [ 'uses' => 'FieldsController@simpleSave', 'as' => 'admin.fields.simple_save' ]);
    });

    // posts
    Route::group([ 'prefix' => 'posts' ], function() {
        Route::get('/', [ 'uses' => 'PostsController@index', 'as' => 'admin.posts.index' ]);
        Route::any('/add/{category_id}', [ 'uses' => 'PostsController@add', 'as' => 'admin.posts.add' ]);
        Route::any('/edit/{id}', [ 'uses' => 'PostsController@edit', 'as' => 'admin.posts.edit' ]);
        Route::get('/delete/{id}', [ 'uses' => 'PostsController@delete', 'as' => 'admin.posts.delete' ]);
        Route::get('/status/{id}', [ 'uses' => 'PostsController@status', 'as' => 'admin.posts.status' ]);
        Route::get('/restore/{id}', [ 'uses' => 'PostsController@restore', 'as' => 'admin.posts.restore' ]);
        Route::post('/simple-save', [ 'uses' => 'PostsController@simpleSave', 'as' => 'admin.posts.simple_save' ]);
    });

    // categories
    Route::group([ 'prefix' => 'categories' ], function() {
	    Route::get('/', [ 'uses' => 'CategoriesController@index', 'as' => 'admin.categories.index' ]);
	    Route::any('/add', [ 'uses' => 'CategoriesController@add', 'as' => 'admin.categories.add' ]);
	    Route::any('/edit/{id}', [ 'uses' => 'CategoriesController@edit', 'as' => 'admin.categories.edit' ]);
	    Route::get('/delete/{id}', [ 'uses' => 'CategoriesController@delete', 'as' => 'admin.categories.delete' ]);
	    Route::any('/order', [ 'uses' => 'CategoriesController@order', 'as' => 'admin.categories.order' ]);
	    Route::post('/simple-save', [ 'uses' => 'CategoriesController@simpleSave', 'as' => 'admin.categories.simple_save' ]);
    });
});
