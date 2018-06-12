<?php
/**
 * Created by PhpStorm.
 * User: Dizy
 * Date: 2018/6/6
 * Time: 1:45
 */

namespace app\api\controller;


use app\common\controller\Api;
use app\common\model\Book;
use app\common\model\BookItem;

class BookController extends Api
{



    /**
     * @param null $type
     * @param int $page
     * @param int $order 0 - id  1 - view count  2 - favor count
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($type = null, $page = 1, $order = 0){
        switch ($order){
            case 0:
            default:
                $order = 'id';
                break;
            case 1:
                $order = 'view_count';
                break;
            case 2:
                $order = 'favor_count';
        }
        $bookBuilder = (new Book());
        if($type)
            $bookBuilder = $bookBuilder->where('type', $type);
        $books = $bookBuilder->order($order, 'DESC')
            ->page($page, 10)
             ->select();
        foreach ($books as $book){
            /**
             * @var Book $book
             */
            $book->author;
        }
        s('success', $books);
    }

    /**
     * @param $id
     * @param int $page
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getItems($id, $page = 1){
        $items = (new BookItem())->where('book_id', $id)
            ->order('order_id', 'ASC')
            ->page($page, 10)
            ->select();
        foreach ($items as $item){
            $item->character;
        }
        s('success', $items);
    }
    
    /**
     * @param null $key_words
     * @param int $page
     * @param int $order
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function searchBook($key_words = null,$page = 1,$order=0){
        switch ($order){
            case 0:
            default:
                $order = 'id';
                break;
            case 1:
                $order = 'view_count';
                break;
            case 2:
                $order = 'favor_count';
        }
        $books = (new Book());
        $books = $books->where('title','like','%'.$key_words.'%')
            ->whereOr('type','like','%'.$key_words.'%')
            ->whereOr('desc','like','%'.$key_words.'%')
            ->order($order,'DESC')
            ->page($page,10)
            ->select();
        $count = $books->count();
        if(!$count)e(1,'no book find');
        s('success',$books);
    }

    /**
     * @param $book_id
     * @throws \think\exception\DbException
     */
    public function view($book_id){
        /**
         * @var Book book
         */
        $book = Book::get($book_id);
        if(is_null($book))
            e(1, 'book not found');
        $book->incrViewCount();
        s();
    }

    /**
     * @param $book_id
     * @throws \think\exception\DbException
     */
    public function info($book_id){
        /**
         * @var Book book
         */
        $book = Book::get($book_id);
        if(is_null($book))
            e(1, 'book not found');
        $book->author;
        s('success', $book);
    }

}
