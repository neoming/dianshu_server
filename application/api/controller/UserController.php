<?php
/**
 * Created by PhpStorm.
 * User: Dizy
 * Date: 2018/6/5
 * Time: 23:25
 */

namespace app\api\controller;


use app\common\controller\Api;
use app\common\model\User;
use app\common\validate\UserValidate;

class UserController extends Api
{
    public function register(){
        $vali = new UserValidate();
        $inputs = input('request.');
        if(!$vali->scene('register')->check($inputs))
            e(1, $vali->getError());
        $inputs['password'] = pwHash($inputs['password']);
        $user = new User();
        $user->allowField(['username', 'password', 'phone'])
            ->save($inputs);
        s('success', [
           'uid' => $user->id
        ]);
    }

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login(){
        $vali = new UserValidate();
        $inputs = input('request.');
        if(!$vali->scene('login')->check($inputs))
            e(1, $vali->getError());
        /**
         * @var User $user
         */
        $user = (new User())->where('username|phone', $inputs['username'])
            ->find();
        if(!$user)
            e(2, 'invalid username or password');
        if(!pwMatch($inputs['password'], $user->password))
            e(3, 'invalid username or password');
        $newToken = $user->updateToken();
        s('success', [
            'uid' => $user->id,
            'api_token' => $newToken
        ]);
    }

    public function info(User $user){
        s('success', $user);
    }
}