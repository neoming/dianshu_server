<?php

/**
 * Created by PhpStorm.
 * User: 何东
 * Date: 2018/6/8
 * Time: 21:07
 */

namespace app\api\controller;

use app\common\controller\Api;
use app\common\model\Book;
use app\common\model\BookScore;
use app\common\model\User;

class CommentController extends Api
{

    /**
     * @param User $user
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function comment(User $user)
    {
        $comm = new BookScore();
        $inputs = input('request.');
        if (is_null((new Book())->where('id', '=', $inputs["book_id"])->find())) {
            e(1, "no such book");
        }
        if (!is_null($comm->where(["user_id" => $user->id, "book_id" => $inputs["book_id"]])->find())) {
            e(2, "you have commented it");
        }
        $inputs['user_id'] = $user->id;
        $comm->allowField("user_id,book_id,score,comment")->save($inputs);
        $comm->book->incrScoreCount();
        $comm->book->incrScoreTotal(intval($inputs['score']));
        s("success");
    }

    /**
     * @param $comment_id
     * @param User $user
     * @throws \think\exception\DbException
     */
    public function edit($comment_id, User $user)
    {
        /**
         * @var BookScore $comm ]
         */
        $comm = BookScore::get($comment_id);
        if (is_null($comm)) {
            e(1, "no such comment");
        }
        if ($comm->user->id != $user->id) {
            e(2, "unauthorized");
        }
        $inputs = input("request.");
        $inputs['score'] = intval($inputs['score']);
        if ($inputs['score'] >= $comm->score) {
            $comm->book->incrScoreTotal($inputs['score'] - $comm->score);
        } else {
            $comm->book->decrScoreTotal($comm->score - $inputs['score']);
        }
        $comm->allowField("score,comment")->save($inputs);
        s("success");
    }

    /**
     * @param $comment_id
     * @param User $user
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function delete($comment_id, User $user)
    {
        /**
         * @var BookScore $comm ]
         */
        $comm = BookScore::get($comment_id);
        if (is_null($comm)) {
            e(1, "no such comment");
        }
        if ($comm->user->id != $user->id) {
            e(2, "unauthorized");
        }
        $comm->book->decrScoreCount();
        $comm->book->decrScoreTotal($comm->score);
        $comm->delete();
        s("success");
    }

    public function get_comment(User $user)
    {
        /**
         * @var BookScore $comm
         */
        $inputs = input('request.');
        if (is_null((new Book())->where('id', '=', $inputs["book_id"])->find())) {
            e(1, "no such book");
        }
        $comm = (new BookScore())->where(["user_id" => $user->id, "book_id" => $inputs['book_id']])->find();
        if (!$comm) {
            e(2, "no comment available");
        }
        $comm->user;
        $comm->book;
        s("success", $comm);
    }

    public function get_comment_list($book_id, $page = 1)
    {
        /**
         * @var Book $book
         */
        $book = Book::get($book_id);
        if(is_null($book)){
            e(1, "no such book");
        }
        $comments=$book->comments()->page($page,10)->select();
        foreach ($comments as $comm){
            $comm->user;
            $comm->book;
        }
        s("success",$comments);
    }

    public function get_my_comment_list($page = 1, User $user)
    {
        $comm = (new BookScore())->where("user_id", '=', $user->id);
        if (is_null($comm)) {
            e(1, "no comments available");
        }
        $comm = $comm->order("update_at", "DESC")
            ->page($page, 10)
            ->select();
        foreach ($comm as $item) {
            $item->user;
            $item->book;
        }
        s("success", $comm);
    }

}