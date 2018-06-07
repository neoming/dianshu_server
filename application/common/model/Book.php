<?php
/**
 * Created by PhpStorm.
 * User: Dizy
 * Date: 2018/6/6
 * Time: 1:42
 */

namespace app\common\model;


use think\Model;

class Book extends Model
{
    public $autoWriteTimestamp = true;

    public function characters(){
        return $this->hasMany('BookCharacter', 'id', 'book_id');
    }
}