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

    public function author(){
        return $this->belongsTo('User', 'user_id', 'id');
    }

    public function characters(){
        return $this->hasMany('BookCharacter', 'book_id', 'id');
    }

    public function items(){
        return $this->hasMany('BookItem','id','book_id');
    }

    public function favors(){
        return $this->hasMany('UserFavor', 'book_id', 'id');
    }

    /**
     * @throws \think\Exception
     */
    public function incrItemsCount(){
        if(is_null($this->items_count)){
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
        if(is_null($this->items_count)){
            $this->items_count = 1;
            $this->save();
        }else{
            $this->setDec('items_count');
        }
    }

    /**
     * @throws \think\Exception
     */
    public function incrFavoredCount(){
        if(is_null($this->favor_count)){
            $this->favor_count = 1;
            $this->save();
        }else{
            $this->setInc('favor_count');
        }
    }

    /**
     * @throws \think\Exception
     */
    public function decrFavoredCount(){
        if(is_null($this->favor_count)){
            $this->favor_count = 0;
            $this->save();
        }else{
            $this->setDec('favor_count');
        }
    }

    public static function exist($id){
        return !!static::get($id);
    }

}