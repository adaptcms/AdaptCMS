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
Auth::routes();

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'role:admin'], function () {
    Route::group([ 'prefix' => 'users' ], function() {
	    Route::get('/', [ 'uses' => 'UsersController@index', 'as' => 'admin.users.index' ]);
	    Route::any('/add', [ 'uses' => 'UsersController@add', 'as' => 'admin.users.add' ]);
	    Route::any('/edit/{id}', [ 'uses' => 'UsersController@edit', 'as' => 'admin.users.edit' ]);
	    Route::get('/delete/{id}', [ 'uses' => 'UsersController@delete', 'as' => 'admin.users.delete' ]);
	    Route::post('/simple-save', [ 'uses' => 'UsersController@simpleSave', 'as' => 'admin.users.simple_save' ]);
	    Route::get('/login-as/{id}', [ 'uses' => 'UsersController@loginAs', 'as' => 'admin.users.login_as' ]);

        Route::get('/oauth/redirect', [ 'uses' => 'OauthController@redirect', 'as' => 'admin.oauth.redirect' ]);
        Route::post('/oauth/callback', [ 'uses' => 'OauthController@callback', 'as' => 'admin.oauth.callback' ]);
    });

	Route::group([ 'prefix' => 'roles', 'namespace' => 'Admin', 'middleware' => 'role:admin' ], function() {
	    Route::get('/', [ 'uses' => 'RolesController@index', 'as' => 'admin.roles.index' ]);
	    Route::any('/add', [ 'uses' => 'RolesController@add', 'as' => 'admin.roles.add' ]);
	    Route::any('/edit/{id}', [ 'uses' => 'RolesController@edit', 'as' => 'admin.roles.edit' ]);
	    Route::get('/delete/{id}', [ 'uses' => 'RolesController@delete', 'as' => 'admin.roles.delete' ]);
    });
});

Route::any('/login', [ 'as' => 'login', 'uses' => 'UsersController@login' ]);

Route::group(['prefix' => 'users'], function () {
	Route::any('/register', [ 'as' => 'register', 'uses' => 'UsersController@register' ]);

    Route::group([ 'middleware' => 'auth' ], function() {
	    Route::get('/profile/edit', [ 'as' => 'users.profile.edit', 'uses' => 'UsersController@profileEdit' ]);
		Route::get('/logout', [ 'as' => 'logout', 'uses' => 'UsersController@logout' ]);
    });
});

Route::get('/profile/{username}', [ 'as' => 'users.profile.view', 'uses' => 'UsersController@profile' ]);
