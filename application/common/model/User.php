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

    protected $hidden = ['password', 'api_token'];

    public function books(){
        return $this->hasMany('Book', 'author_id', 'id');
    }

    public function followings(){
        return $this->hasMany('UserFollow', 'from_user_id', 'id');
    }

    public function followedBys(){
        return $this->hasMany('UserFollow', 'to_user_id', 'id');
    }

    public function favorings(){
        return $this->hasMany('UserFavor', 'user_id', 'id');
    }

    /**
     * @throws \think\Exception
     */
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

    public function incrFollowingCount(){
        if(is_null($this->following_count)){
            $this->following_count = 1;
            $this->save();
        }else{
            $this->setInc('following_count');
        }
    }

    public function decrFollowingCount(){
        if(is_null($this->following_count)){
            $this->following_count = 0;
            $this->save();
        }else{
            $this->setDec('following_count');
        }
    }

    public function incrFollowedCount(){
        if(is_null($this->followed_by_count)){
            $this->followed_by_count = 1;
            $this->save();
        }else{
            $this->setInc('followed_by_count');
        }
    }

    public function decrFollowedCount(){
        if(is_null($this->followed_by_count)){
            $this->followed_by_count = 0;
            $this->save();
        }else{
            $this->setDec('followed_by_count');
        }
    }

    public function incrFavoringCount(){
        if(is_null($this->favoring_count)){
            $this->favoring_count = 1;
            $this->save();
        }else{
            $this->setInc('favoring_count');
        }
    }

    public function decrFavoringCount(){
        if(is_null($this->favoring_count)){
            $this->favoring_count = 0;
            $this->save();
        }else{
            $this->setDec('favoring_count');
        }
    }

    public static function exist($id){
        return !!static::get($id);
    }
}