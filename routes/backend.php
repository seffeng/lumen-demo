<?php

use Illuminate\Support\Facades\Route;
use App\Common\Constants\FromConst;

Route::get('/', function() {
    //var_dump('admin.index');
    return view('home');
});

Route::get('home', 'Test\SiteController@home');

Route::group(['prefix' => 'test', 'namespace' => 'Test'], function() {
    Route::get('/index', 'SiteController@index');
});

Route::group(['namespace' => 'Auth', 'middleware' => ['log.login:' . FromConst::BACKEND]], function() {
    Route::post('/login', 'SiteController@login');
    Route::get('/check-login', 'SiteController@isLogin');
    Route::delete('/logout', 'SiteController@logout');
});

Route::group(['middleware' => ['check.login:backend', 'log.operate:' . FromConst::BACKEND]], function() {
    Route::group(['namespace' => 'Site'], function() {
        Route::get('/down-list', 'SiteController@getDownList');
    });

    Route::group(['namespace' => 'Log'], function() {
        Route::get('/operate-log', 'SiteController@operateLog');
        Route::get('/admin/login-log', 'SiteController@adminLoginLog');
    });

    Route::group(['namespace' => 'Auth'], function() {
        Route::put('/auth', 'SiteController@update');
        Route::get('/auth', 'SiteController@info');
    });

    Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function() {
        Route::get('', 'SiteController@index');
        Route::post('', 'SiteController@create');
        Route::put('', 'SiteController@update');
        Route::delete('/{id:[0-9]+}', 'SiteController@delete');
        Route::put('/on/{id:[0-9]+}', 'SiteController@on');
        Route::put('/off/{id:[0-9]+}', 'SiteController@off');
    });
});
