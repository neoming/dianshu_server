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

}