<?php
/**
 * Created by PhpStorm.
 * User: Dizy
 * Date: 2018/6/5
 * Time: 23:40
 */

namespace app\common\facade;


use app\common\model\User;
use app\common\provider\AuthServiceProvider;
use think\Facade;

/**
 * Class Auth
 * @package app\common\facade
 * @method bool check(callable $checkFunc) static
 * @method User|null user() static
 */
class Auth extends Facade
{
    protected static function getFacadeClass()
    {
        return AuthServiceProvider::class;
    }
}