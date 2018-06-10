<?php


use think\facade\Route;

Route::group('api', function(){

    Route::group('upload', function(){

        Route::any('image', 'api/UploadController/image');

    })->middleware('apiAuth');


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

        Route::any('profile/:id','api/UserController/profile')
            ->pattern('id', '\d+');

        Route::any('follow', 'api/UserController/follow');

        Route::any('unfollow', 'api/UserController/unfollow');

        Route::any('get_followings', 'api/UserController/getFollowingList');

        Route::any('get_followed_bys', 'api/UserController/getFollowedByList');

        Route::any('favor', 'api/UserFavorController/favor');

        Route::any('unfavor', 'api/UserFavorController/unfavor');

        Route::any('get_favorings', 'api/UserFavorController/getFavoringList');

        Route::any('is_favored', 'api/UserFavorController/isFavored');

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

    //书内容(需验证)
    Route::group('book/item',function (){
        Route::any('add','api/BookEditController/itemAdd');
        Route::any('edit','api/BookEditController/itemEdit');
        Route::any('remove','api/BookEditController/itemRemove');
        Route::any('move','api/BookEditController/itemMove');
    })->middleware('apiAuth');

    //书人物(需验证)
    Route::group('book/character',function (){
        Route::any('add','api/BookCharacterController/add');
        Route::any('edit','api/BookCharacterController/edit');
        Route::any('remove','api/BookCharacterController/remove');
        Route::any('characters','api/BookCharacterController/getCharacterList');
    })->middleware('apiAuth');

    //书相关(不需验证)
    Route::group('book', function(){

        //获得书内容
        Route::any(':id', 'api/BookController/getItems')
            ->pattern('id', '\d+');
        //search book
        Route::any('search','api/BookController/searchBook');
        //取得书列表
        Route::any('/$', 'api/BookController/getList');
        Route::any('all', 'api/BookController/getList');
        Route::any(':type', 'api/BookController/getList')
            ->pattern('type', '[\w\-]+');
    });

    //用户评价(需验证)
    Route::group('comment',function (){

        Route::any('comment','api/CommentController/comment');

        Route::any('edit','api/CommentController/edit');

        Route::any('delete','api/CommentController/delete');

        Route::any('get_comment','api/CommentController/get_comment');

        Route::any('get_my_comment_list','api/CommentController/get_my_comment_list');

        Route::any('info','api/CommentController/info');

    })->middleware('apiAuth');

});
