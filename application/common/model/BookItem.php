<?php
/**
 * Created by PhpStorm.
 * User: Dizy
 * Date: 2018/6/6
 * Time: 2:06
 */

namespace app\common\model;


use think\Model;

class BookItem extends Model
{
    public $autoWriteTimestamp = true;

    public function book(){
        return $this->belongsTo('Book', 'book_id', 'id');
    }

    public function character(){
        return $this->belongsTo('BookCharacter', 'character_id', 'id')->field('name,avatar');
    }
}