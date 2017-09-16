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

Route::group(['prefix' => 'community'], function () {
    Route::get('/', [ 'uses' => '\App\Modules\Adaptbb\Http\Controllers\ForumsController@index', 'as' => 'plugin.adaptbb.forums.index' ]);
    Route::get('/category/{slug}', [ 'uses' => '\App\Modules\Adaptbb\Http\Controllers\ForumCategoriesController@view', 'as' => 'plugin.adaptbb.categories.view' ]);
    Route::get('/forum/{slug}', [ 'uses' => '\App\Modules\Adaptbb\Http\Controllers\ForumsController@view', 'as' => 'plugin.adaptbb.forums.view' ]);

    Route::group([ 'middleware' => 'auth' ], function() {
	   Route::any('/topic/{forum_slug}/add', [ 'uses' => '\App\Modules\Adaptbb\Http\Controllers\TopicsController@add', 'as' => 'plugin.adaptbb.topics.add' ]);
    });

    Route::any('/topic/{id}/reply', [ 'uses' => '\App\Modules\Adaptbb\Http\Controllers\TopicsController@reply', 'as' => 'plugin.adaptbb.topics.reply' ]);
    Route::get('/topic/{forum_slug}/{topic_slug}', [ 'uses' => '\App\Modules\Adaptbb\Http\Controllers\TopicsController@view', 'as' => 'plugin.adaptbb.topics.view' ]);
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'role:admin'], function () {
  Route::group([ 'prefix' => 'adaptbb' ], function() {
    // categories
    Route::group([ 'prefix' => 'forum_categories' ], function() {
      Route::get('/', [ 'uses' => 'ForumCategoriesController@index', 'as' => 'plugin.adaptbb.admin.forum_categories.index' ]);
      Route::any('/add', [ 'uses' => 'ForumCategoriesController@add', 'as' => 'plugin.adaptbb.admin.forum_categories.add' ]);
      Route::any('/edit/{id}', [ 'uses' => 'ForumCategoriesController@edit', 'as' => 'plugin.adaptbb.admin.forum_categories.edit' ]);
      Route::get('/delete/{id}', [ 'uses' => 'ForumCategoriesController@delete', 'as' => 'plugin.adaptbb.admin.forum_categories.delete' ]);
      Route::any('/order', [ 'uses' => 'ForumCategoriesController@order', 'as' => 'plugin.adaptbb.admin.forum_categories.order' ]);
    });

    Route::group([ 'prefix' => 'forums' ], function() {
      Route::get('/', [ 'uses' => 'ForumController@index', 'as' => 'plugin.adaptbb.admin.forums.index' ]);
      Route::any('/add', [ 'uses' => 'ForumController@add', 'as' => 'plugin.adaptbb.admin.forums.add' ]);
      Route::any('/edit/{id}', [ 'uses' => 'ForumController@edit', 'as' => 'plugin.adaptbb.admin.forums.edit' ]);
      Route::get('/delete/{id}', [ 'uses' => 'ForumController@delete', 'as' => 'plugin.adaptbb.admin.forums.delete' ]);
      Route::any('/order', [ 'uses' => 'ForumController@order', 'as' => 'plugin.adaptbb.admin.forums.order' ]);
    });
  });
});
