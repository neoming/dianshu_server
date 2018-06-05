<?php


use think\facade\Route;

Route::group('api', function(){

    //用户相关(不需验证)
    Route::group('user', function(){
        Route::any('register', 'api/UserController/register');
        Route::any('login', 'api/UserController/login');
    });

    //用户相关(需验证)
    Route::group('user', function(){
        Route::any('info', 'api/UserController/info');
    })->middleware('apiAuth');
});