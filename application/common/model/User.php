<?php
/**
 * Created by PhpStorm.
 * User: Dizy
 * Date: 2018/6/5
 * Time: 22:26
 */

namespace app\common\model;


use think\Model;

class User extends Model
{
    public $autoWriteTimestamp = true;
    protected $readonly = ['id'];

    public function books(){
        return $this->hasMany('Book', 'author_id', 'id');
    }

    public function incrWorkCount(){
        if($this->work_count === null){
            $this->work_count = 1;
            $this->save();
        }else{
            $this->setInc('work_count');
        }
    }

    public function updateToken(){
        $this->api_token = rand_str(24);
        $this->updateLastActive();
        $this->save();
        return $this->api_token;
    }

    public function updateLastActive(){
        $this->last_active = time();
        $this->save();
    }
}