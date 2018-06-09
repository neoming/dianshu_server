<?php
/**
 * Created by PhpStorm.
 * User: mhisf
 * Date: 2018/6/9
 * Time: 17:50
 */

namespace app\common\model;


use think\Model;

class UserFollow extends Model
{
    public $autoWriteTimestamp = true;

    public function fromUser(){
        return $this->belongsTo("User", "from_user_id", "id");
    }

    public function toUser(){
        return $this->belongsTo("User", "to_user_id", "id");
    }
}