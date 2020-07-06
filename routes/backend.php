<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    //var_dump('admin.index');
    return view('home');
});

Route::group(['namespace' => 'Site'], function() {
    Route::get('/down-list', 'SiteController@getDownList');
});

Route::group(['namespace' => 'Auth'], function() {
    Route::post('/login', 'SiteController@login');
    Route::get('/check-login', 'SiteController@isLogin');
    Route::post('/logout', 'SiteController@logout');
});

Route::group(['namespace' => 'Auth', 'middleware' => ['checkLogin:backend']], function() {
    Route::put('/auth', 'SiteController@update');
    Route::get('/auth', 'SiteController@info');
});

Route::group(['middleware' => ['checkLogin:backend']], function() {
    Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function() {
        Route::get('/index', 'SiteController@index');
        Route::post('/index', 'SiteController@create');
        Route::put('/index', 'SiteController@update');
        Route::delete('/index', 'SiteController@delete');
    });
});
