<?php
/**
 * Created by PhpStorm.
 * User: Dizy
 * Date: 2018/6/5
 * Time: 23:38
 */

namespace app\common\provider;

use app\common\model\User;
use think\Model;

class AuthServiceProvider
{
    /**
     * @var Model|null $userModel
     */
    protected $userModel;

    /**
     * @param callable $checkFunc
     * @return bool
     */
    public function check($checkFunc){
        if(is_callable($checkFunc)){
            $user = $checkFunc();
            if($user instanceof Model){
                $this->userModel = $user;
                bind(User::class, $user);
                return true;
            }
        }
        return false;
    }

    /**
     * @return Model|null
     */
    public function user(){
        return $this->userModel;
    }
}