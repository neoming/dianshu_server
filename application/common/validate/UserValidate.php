<?php
/**
 * Created by PhpStorm.
 * User: Dizy
 * Date: 2018/6/6
 * Time: 0:19
 */

namespace app\common\validate;


use think\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'username' => 'require|chsDash|length:4,24',
        'password' => 'require|length:8,32',
        'phone' => ['require', 'regex' => '/^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\d{8}$/'],
        'birthday' => 'date',
    ];

    protected $message = [
        'username.require' => 'username cannot be empty',
        'username.chsDash' => 'username cannot include special characters',
        'username.length' => 'username\'s length can only be 4-24',
        'password.require' => 'password cannot be empty',
        'password.length' => 'password\'s length can only be 8-32',
        'phone.require' => 'phone cannot be empty',
        'phone.regex' => 'invalid phone number',
        'birthday.date' => 'invalid birthday format',
    ];

    protected $scene = [
        'login' => ['username', 'password'],
    ];

    public function sceneRegister(){
        return $this->only(['username', 'password', 'phone'])
            ->append('username', 'unique:user')
            ->message('username.unique', 'username already exists')
            ->append('phone', 'unique:user')
            ->message('phone.unique', 'phone already exists');
    }
}