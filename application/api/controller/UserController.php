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
use app\common\model\UserFollow;
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

    /**
     * @param User $user
     * @param $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function profile(User $user, $id){
        if($user->id == $id)
            $this->info($user);
        $requestedUser = User::get($id);
        if(is_null($requestedUser))
            e(1, 'user not exist');
        $sered = $requestedUser->toArray();
        $sered['followed'] = $requestedUser->followedBys()->where('from_user_id', $user->id)->find();
        s('success', $sered);
    }

    /**
     * @param User $user
     * @param $to_uid
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function follow(User $user, $to_uid){
        if($user->id == $to_uid){
            e(1, 'one cannot follow itself');
        }
        $userFollow = (new UserFollow())->where(['from_user_id' => $user->id, 'to_user_id' => $to_uid])->find();
        if(is_null($userFollow)){
            if(!User::exist($to_uid))
                e(2, 'user not exist');
            $userFollow = new UserFollow();
            $userFollow->from_user_id = $user->id;
            $userFollow->to_user_id = $to_uid;
            $userFollow->status = 0;
            $userFollow->save();
        }
        if($userFollow->status === 1)
            e(3, 'already followed');
        $userFollow->status = 1;
        $userFollow->from_user->incrFollowingCount();
        $userFollow->to_user->incrFollowedCount();
        $userFollow->save();
        s();
    }

    /**
     * @param User $user
     * @param $to_uid
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function unfollow(User $user, $to_uid){
        if($user->id == $to_uid){
            e(1, 'one cannot unfollow itself');
        }
        $userFollow = (new UserFollow())->where(['from_user_id' => $user->id, 'to_user_id' => $to_uid])->find();
        if(is_null($userFollow) || $userFollow->status === 0){
            e(2, 'not followed yet');
        }
        $userFollow->status = 0;
        $userFollow->from_user->decrFollowingCount();
        $userFollow->to_user->decrFollowedCount();
        $userFollow->save();
        s();
    }

    public function getFollowingList(User $user, $page = 1){
        $follows = $user->followings()->where('status', 1)->page($page, 15)->select();
        foreach ($follows as $follow){
            $follow->to_user;
        }
        s('success', $follows);
    }

    public function getFollowedByList(User $user, $page = 1){
        $follows = $user->followedBys()->where('status', 1)->page($page, 15)->select();
        foreach ($follows as $follow){
            $follow->from_user;
        }
        s('success', $follows);
    }

}