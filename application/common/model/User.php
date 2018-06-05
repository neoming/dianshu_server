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