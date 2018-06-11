<?php

/**
 * Created by PhpStorm.
 * User: 何东
 * Date: 2018/6/8
 * Time: 21:01
 */


namespace app\common\model;

use think\Model;


class BookScore extends Model

{

    //public $autoWriteTimestamp = true;
    protected $readonly = ['id'];

    public function bookInfo(){
        return $this->belongsTo("Book","book_id","id");
    }

    public function userInfo(){
        return $this->belongsTo("User","user_id","id");
    }

    public function createTime(){
        $this->create_at=time();
        $this->save();
    }

    public function updateTime(){
        $this->update_at=time();
        $this->save();
    }

}