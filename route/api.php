<?php


use think\facade\Route;

Route::group('api', function(){

    //用户相关(不需验证)
    Route::group('user', function(){

        //注册
        Route::any('register', 'api/UserController/register');

        //登录
        Route::any('login', 'api/UserController/login');
    });

    //用户相关(需验证)
    Route::group('user', function(){

        Route::any('info', 'api/UserController/info');
    })->middleware('apiAuth');


    //书编辑相关(需验证)
    Route::group('book/edit', function(){

        //获得我的作品
        Route::any('my', 'api/BookEditController/getMyWorks');

        //添加书
        Route::any('add', 'api/BookEditController/add');

        //指定书操作
        Route::group(':id', function(){

            //删除书
            Route::any('remove', 'api/BookEditController/remove');
        })->pattern('id', '\d+');
    })->middleware('apiAuth');

    Route::group('book/item',function (){
        Route::any('add','api/BookEditController/itemAdd');
        Route::any('edit','api/BookEditController/itemEdit');
        Route::any('remove','api/BookEditController/itemRemove');
        Route::any('move','api/BookEditController/itemMove');
    })->middleware('apiAuth');

    //书相关(不需验证)
    Route::group('book', function(){

        //获得书内容
        Route::any(':id', 'api/BookController/getItems')
            ->pattern('id', '\d+');

        //取得书列表
        Route::any('/', 'api/BookController/getList');
        Route::any('all', 'api/BookController/getList');
        Route::any(':type', 'api/BookController/getList');
    });

//需要验证

    Route::group('comment',function (){

        Route::any('comment','api/CommentController/comment');

        Route::any('edit','api/CommentController/edit');

        Route::any('delete','api/CommentController/delete');

        Route::any('get_comment','api/CommentController/get_comment');

        Route::any('get_my_comment_list','api/CommentController/get_my_comment_list');

        Route::any('info','api/CommentController/info');

    })->middleware('apiAuth');

});