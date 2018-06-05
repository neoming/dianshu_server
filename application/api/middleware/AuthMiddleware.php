<?php
/**
 * Created by PhpStorm.
 * User: Dizy
 * Date: 2018/6/5
 * Time: 23:45
 */

namespace app\api\middleware;


use app\common\facade\Auth;
use app\common\model\User;
use think\Request;

class AuthMiddleware
{
    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed|\think\response\Redirect
     */
    public function handle($request, \Closure $next)
    {
        if(!Auth::check(function() use ($request){
            /**
             * @var User $user
             */
            $user = (new User())->where('id', $request->request('uid'))
                ->where('api_token', $request->request('api_token'))
                ->find();
            if($user) $user->updateLastActive();
            return $user;
        })){
            e(100, 'unauthorized');
        }
        return $next($request);
    }

}