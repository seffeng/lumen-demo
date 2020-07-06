<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    //var_dump('www.index');
    return view('home');
});

Route::get('home', 'Test\SiteController@home');

Route::group(['prefix' => 'test', 'namespace' => 'Test'], function() {
    Route::get('/index', 'SiteController@index');
});

Route::group(['namespace' => 'Site'], function() {
    Route::get('/down-list', 'SiteController@getDownList');
});

Route::group(['namespace' => 'Auth'], function() {
    Route::post('/login', 'SiteController@login');
    Route::get('/check-login', 'SiteController@isLogin');
    Route::post('/logout', 'SiteController@logout');
});

Route::group(['namespace' => 'Auth', 'middleware' => ['checkLogin:frontend']], function() {
    Route::put('/auth', 'SiteController@update');
    Route::get('/auth', 'SiteController@info');
});