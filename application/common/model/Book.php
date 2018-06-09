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

    public function items(){
        return $this->hasMany('BookItem','id','book_id');
    }

    /**
     * @throws \think\Exception
     */
    public function incrItemsCount(){
        if($this->items_count === null){
            $this->items_count = 1;
            $this->save();
        }else{
            $this->setInc('items_count');
        }
    }

    /**
     * @throws \think\Exception
     */
    public function decrItemsCount(){
        if($this->items_count === null){
            $this->items_count = 1;
            $this->save();
        }else{
            $this->setDec('items_count');
        }
    }
}