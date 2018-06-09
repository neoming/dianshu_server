<?php

/**
 * Created by PhpStorm.
 * User: 何东
 * Date: 2018/6/8
 * Time: 21:07
 */

namespace app\api\controller;

use app\common\controller\Api;
use app\common\model\Book;
use app\common\model\BookScore;
use app\common\model\User;

class CommentController extends Api
{

    public function comment(User $user){
        $comm=new BookScore();
        $inputs=input('request.');
        if(is_null((new Book())->where('id','=',$inputs["book_id"])->find())){
            e(1,"no such book");
        }
        if(!is_null($comm->where(["user_id"=>$user->id,"book_id"=>$inputs["book_id"]])->find())){
            e(1,"you have commented it");
        }
        $comm->createTime();
        $comm->updateTime();
        $comm->user_id=$user->id;
        $comm->allowField("user_id,book_id,score,comment")->save($inputs);
        s("success");
    }

    public function edit(User $user){
        /**
         * @var BookScore $comm
         */
        $inputs=input('request.');
        if(is_null((new Book())->where('id','=',$inputs["book_id"])->find())){
            e(1,"no such book");
        }
        $comm=(new BookScore())->where(["user_id"=>$user->id,"book_id"=>$inputs["book_id"]])->find();
        $comm->updateTime();
        $comm->allowField("score,comment")->save($inputs);
        s("success");
    }

    public function delete(User $user){
        $inputs=input('request.');
        if(is_null((new Book())->where('id','=',$inputs["book_id"])->find())){
            e(1,"no such book");
        }
        $comm=(new BookScore())->where(["user_id"=>$user->id,"book_id"=>$inputs["book_id"]]);
        $comm->delete();
        s("success");
    }

    public function get_comment(User $user){
        /**
         * @var BookScore $comm
         */
        $inputs=input('request.');
        if(is_null((new Book())->where('id','=',$inputs["book_id"])->find())){
            e(1,"no such book");
        }
        $comm=(new BookScore())->where(["user_id"=>$user->id,"book_id"=>$inputs['book_id']])->find();
        if(!$comm){
            e(1,"no comment available");
        }
        $comm->userinfo;
        $comm->bookinfo;
        s("success",$comm);
    }

    public function get_my_comment_list($page=0,User $user){
        $inputs=input('request.');
        $comm=(new BookScore())->where("user_id",'=',$user->id);
        if(is_null($comm)) {
            e(1, "no comments available");
        }
        $comm=$comm->order("update_at","DESC")->limit($page*10,10)->select();
        foreach ($comm as $item){
            $item->userInfo;
            $item->bookInfo;
        }
        s("success",$comm);
    }

}