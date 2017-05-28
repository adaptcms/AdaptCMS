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

Route::any('/login', [ 'as' => 'login', 'uses' => '\App\Modules\Users\Http\Controllers\UsersController@login' ]);

Route::group(['prefix' => 'users'], function () {
	Route::any('/register', [ 'as' => 'register', 'uses' => '\App\Modules\Users\Http\Controllers\UsersController@register' ]);

    // Route::get('/forgot-password', array('as' => 'forgot_password', 'uses' => '\App\Modules\Users\Http\Controllers\Auth\ForgotPasswordController@getRemind'));
    // Route::post('/forgot-password/submit', array('as' => 'forgot_password_submit', 'uses' => '\App\Modules\Users\Http\Controllers\ForgotPasswordController@postRemind'));

    // Route::get('/password/reset/{token}', array('as' => 'reset_password', 'uses' => '\App\Modules\Users\Http\Controllers\ForgotPasswordController@getReset'));
    // Route::post('/password/reset/submit', array('as' => 'reset_password_submit', 'uses' => '\App\Modules\Users\Http\Controllers\ForgotPasswordController@postReset'));

    Route::group([ 'middleware' => 'auth' ], function() {
	    Route::get('/profile/edit', [ 'as' => 'users.profile.edit', 'uses' => '\App\Modules\Users\Http\Controllers\UsersController@profileEdit' ]);
		Route::get('/logout', [ 'as' => 'logout', 'uses' => '\App\Modules\Users\Http\Controllers\UsersController@logout' ]);
    });
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth.admin'], function () {
    Route::group([ 'prefix' => 'users' ], function() {
    Route::get('/', [ 'uses' => '\App\Modules\Users\Http\Controllers\UsersEngineController@index', 'as' => 'admin.users.index' ]);
    Route::any('/add', [ 'uses' => '\App\Modules\Users\Http\Controllers\UsersEngineController@add', 'as' => 'admin.users.add' ]);
    Route::any('/edit/{id}', [ 'uses' => '\App\Modules\Users\Http\Controllers\UsersEngineController@edit', 'as' => 'admin.users.edit' ]);
    Route::get('/delete/{id}', [ 'uses' => '\App\Modules\Users\Http\Controllers\UsersEngineController@delete', 'as' => 'admin.users.delete' ]);
    Route::post('/simple-save', [ 'uses' => '\App\Modules\Users\Http\Controllers\UsersEngineController@simpleSave', 'as' => 'admin.users.simple_save' ]);
    Route::get('/login-as/{id}', [ 'uses' => '\App\Modules\Users\Http\Controllers\UsersEngineController@loginAs', 'as' => 'admin.users.login_as' ]);
    });
});

Route::get('/profile/{username}', [ 'as' => 'users.profile.view', 'uses' => '\App\Modules\Users\Http\Controllers\UsersController@profile' ]);
