<?php
/**
 * Created by PhpStorm.
 * User: mhisf
 * Date: 2018/6/9
 * Time: 19:09
 */

namespace app\common\model;


use think\Model;

class UserFavor extends Model
{
    public $autoWriteTimestamp = true;

    public function user(){
        return $this->belongsTo('User', 'user_id', 'id');
    }

    public function book(){
        return $this->belongsTo('Book', 'book_id', 'id');
    }
}