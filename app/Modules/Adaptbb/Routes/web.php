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

Route::group(['prefix' => 'admin', 'middleware' => 'role:admin'], function () {
  Route::group([ 'prefix' => 'adaptbb' ], function() {
    // categories
    Route::group([ 'prefix' => 'forum_categories' ], function() {
      Route::get('/', [ 'uses' => '\App\Modules\Adaptbb\Http\Controllers\ForumCategoriesAdminController@index', 'as' => 'plugin.adaptbb.admin.forum_categories.index' ]);
      Route::any('/add', [ 'uses' => '\App\Modules\Adaptbb\Http\Controllers\ForumCategoriesAdminController@add', 'as' => 'plugin.adaptbb.admin.forum_categories.add' ]);
      Route::any('/edit/{id}', [ 'uses' => '\App\Modules\Adaptbb\Http\Controllers\ForumCategoriesAdminController@edit', 'as' => 'plugin.adaptbb.admin.forum_categories.edit' ]);
      Route::get('/delete/{id}', [ 'uses' => '\App\Modules\Adaptbb\Http\Controllers\ForumCategoriesAdminController@delete', 'as' => 'plugin.adaptbb.admin.forum_categories.delete' ]);
      Route::any('/order', [ 'uses' => '\App\Modules\Adaptbb\Http\Controllers\ForumCategoriesAdminController@order', 'as' => 'plugin.adaptbb.admin.forum_categories.order' ]);
    });

    Route::group([ 'prefix' => 'forums' ], function() {
      Route::get('/', [ 'uses' => '\App\Modules\Adaptbb\Http\Controllers\ForumsAdminController@index', 'as' => 'plugin.adaptbb.admin.forums.index' ]);
      Route::any('/add', [ 'uses' => '\App\Modules\Adaptbb\Http\Controllers\ForumsAdminController@add', 'as' => 'plugin.adaptbb.admin.forums.add' ]);
      Route::any('/edit/{id}', [ 'uses' => '\App\Modules\Adaptbb\Http\Controllers\ForumsAdminController@edit', 'as' => 'plugin.adaptbb.admin.forums.edit' ]);
      Route::get('/delete/{id}', [ 'uses' => '\App\Modules\Adaptbb\Http\Controllers\ForumsAdminController@delete', 'as' => 'plugin.adaptbb.admin.forums.delete' ]);
      Route::any('/order', [ 'uses' => '\App\Modules\Adaptbb\Http\Controllers\ForumsAdminController@order', 'as' => 'plugin.adaptbb.admin.forums.order' ]);
    });
  });
});
