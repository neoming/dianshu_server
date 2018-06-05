<?php
/**
 * Created by PhpStorm.
 * User: Dizy
 * Date: 2018/6/6
 * Time: 2:33
 */

namespace app\api\controller;


use app\common\controller\Api;
use app\common\model\Book;
use app\common\model\User;
use app\common\validate\BookValidate;

class BookEditController extends Api
{
    public function getMyWorks(User $user){
        s('success', $user->books);
    }

    /**
     * @param User $user
     */
    public function add(User $user){
        $inputs = input('request.');
        $vali = new BookValidate();
        if(!$vali->scene('add')->check($inputs))
            e(1, $vali->getError());
        $inputs['author_id'] = $user->id;
        $book = new Book();
        $book->allowField(['title', 'type', 'desc', 'cover_img', 'author_id'])
            ->save($inputs);
        $user->incrWorkCount();
        s('success', ['book_id' => $book->id]);
    }

    /**
     * @param $id
     * @param User $user
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function remove($id, User $user){
        $count = $user->books()->where('id', $id)->delete();
        if($count === 0)
            e(1, 'not found among my works');
        s('success');
    }

    /**
     * @param $id
     * @param User $user
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function info($id, User $user){
        $inputs = input('request.');
        $vali = new BookValidate();
        if(!$vali->scene('editInfo')->check($inputs)){
            e(1, $vali->getError());
        }
        $book = $user->books()->where('id', $id)->find();
        if(!$book)
            e(2, 'not found among my works');
        $book->save($inputs);
    }

}