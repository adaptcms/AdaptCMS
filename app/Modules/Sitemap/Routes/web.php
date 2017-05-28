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

Route::group(['prefix' => 'sitemap'], function() {
    Route::get('/urllist.txt', [ 'uses' => '\App\Modules\Sitemap\Http\Controllers\SitemapController@urlList', 'as' => 'sitemap.view' ]);
    Route::get('/{module_type?}.xml', [ 'uses' => '\App\Modules\Sitemap\Http\Controllers\SitemapController@index', 'as' => 'sitemap.view.xml' ]);
    Route::get('/{module_type?}', [ 'uses' => '\App\Modules\Sitemap\Http\Controllers\SitemapController@index', 'as' => 'sitemap.view' ]);
});
