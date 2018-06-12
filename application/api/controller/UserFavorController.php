<?php
/**
 * Created by PhpStorm.
 * User: mhisf
 * Date: 2018/6/9
 * Time: 19:17
 */

namespace app\api\controller;


use app\common\controller\Api;
use app\common\model\Book;
use app\common\model\User;
use app\common\model\UserFavor;

class UserFavorController extends Api
{
    /**
     * @param User $user
     * @param $book_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function favor(User $user, $book_id){
        $userFavor = (new UserFavor())->where(['user_id'=>$user->id, 'book_id'=>$book_id])->find();
        if(is_null($userFavor)){
            if(!Book::exist($book_id))
                e(1, 'book not exists');
            $userFavor = new UserFavor();
            $userFavor->user_id = $user->id;
            $userFavor->book_id = $book_id;
            $userFavor->favored = 0;
            $userFavor->save();
        }
        if($userFavor->favored === 1)
            e(2, 'already favored');
        $userFavor->favored = 1;
        $userFavor->book->incrFavoredCount();
        $userFavor->user->incrFavoringCount();
        $userFavor->save();
        s();
    }

    /**
     * @param User $user
     * @param $book_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function unfavor(User $user, $book_id){
        $userFavor = (new UserFavor())->where(['user_id'=>$user->id, 'book_id'=>$book_id])->find();
        if(is_null($userFavor) || $userFavor->favored === 0){
            e(2, 'not favored yet');
        }
        $userFavor->favored = 0;
        $userFavor->book->decrFavoredCount();
        $userFavor->user->decrFavoringCount();
        $userFavor->save();
        s();
    }

    public function getFavoringList($user_id, $page = 1){
        $user = User::get($user_id);
        if(is_null($user))
            e(1, 'user not found');
        $favorings = $user->favorings()->where('favored',1)
            ->page($page, 10)
            ->select();
        foreach ($favorings as $favor){
            $favor->book->author;
        }
        s('success', $favorings);
    }

    public function isFavored(User $user, $book_id){
        $favor = $user->favorings()->where('favored', 1)->where('book_id', $book_id)->find();
        if(is_null($favor))
            s('success', ['is_favored' => 0]);
        else
            s('success', ['is_favored' => 1]);
    }

}