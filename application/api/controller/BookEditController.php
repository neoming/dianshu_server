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
use app\common\model\BookItem;
use app\common\model\User;
use app\common\validate\BookItemValidate;
use app\common\validate\BookValidate;

class BookEditController extends Api
{
    public function getMyWorks(User $user){
        s('success', $user->books);
    }

    /**
     * @param User $user
     * @throws \think\Exception
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

    /**
     * @param User $user
     * @param $id
     * @param $book_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function itemAdd(User $user,$book_id){
        $inputs = input('request.');
        $vali = new BookItemValidate();
        if(!$vali->scene('itemAdd')->check($inputs))
            e(1, $vali->getError());
        $book = Book::where('id',$book_id)->find();
        if(!$book)e(2,'error book id');
        if($user->id != $book->author_id)e(3,'error user id');
        $bookitem = new BookItem();
        $order_id = $book->items_count;
        $book->incrItemsCount();
        $inputs['order_id'] = $order_id;
        $bookitem->allowField(['book_id','type', 'position', 'character_id','content','order_id'])
            ->save($inputs);
        s('success'.$order_id, $bookitem);
    }

    /**
     * @param $item_id
     * @param User $user
     * @param $book_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function itemEdit($item_id,User $user,$book_id){
        $inputs = input('request.');
        $vali = new BookItemValidate();
        if(!$vali->scene('itemEdit')->check($inputs))
            e(1,$vali->getError());
        $book = Book::where('id',$book_id)->find();
        if($book->author_id != $user->id)e(2,"error user id");
        $bookitem = $book->Items()->where('id', $item_id)->find();
        if(!$bookitem)e(3,'error item id');
        $bookitem->allowField(['type', 'position', 'character_id','content'])
            ->save($inputs);
        s('success',$bookitem);
    }

    /**
     * @param $order_id
     * @param User $user
     * @param $book_id
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function itemRemove($id,User $user){
        $inputs = input('request.');
        $vali = new BookItemValidate();
        if(!$vali->scene('itemRemove')->check($inputs))
            e(1,$vali->getError());
        //delete items
        /**
         * @var BookItem $bookitem
         */
        $bookitem = (new BookItem())->find(["id" => $id]);
        if(!$bookitem)
            e(2, 'not found among the bookitems');
        /**
         * @var Book $book
         */
        $book = $bookitem->book;
        if($book->author_id != $user->id)
            e(3, 'access denied');
        $order_id = $bookitem->order_id;
        $bookitem->delete();
        //update items order and update the items count of book
        $bookItems = $book->items()->where('order_id','>',$order_id)->select();
        foreach ($bookItems as $item){
            $item->setDec('order_id');
            $item->save();
        }
        $book->decrItemsCount();
        s('success');
    }

    /**
     * @param $id
     * @param $dest_id
     * @param User $user
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function itemMove($id,$dest_id,User $user){
        //validate
        $inputs = input('request.');
        $vali = new BookItemValidate();
        if(!$vali->scene('itemMove')->check($inputs))
            e(1,$vali->getError());

        //get the target bookitem and validate the book
        $targetitem = BookItem::where(['id'=>$id])->find();
        if(!$targetitem)e(2,'no such book item');
        $book = $targetitem->book;
        if(!$book)e(3,'no such a book');
        if($book->id!=$targetitem->book_id)e(4,'the book do not have such item');

        //validate the dest_item
        $dest_item = BookItem::where(['id'=>$dest_id])->find();
        if(!$dest_item)e(4,'no such dest_item');
        $target_order = $targetitem->order_id;
        if($target_order==$dest_id)e(5,"param error");
        elseif($target_order>$dest_id){
            $book_items = $book->items()->whereBetween('order_id',[$dest_id,$target_order-1])->select();
            foreach ($book_items as $item){
                $item->setInc('order_id');
                $item->save();
            }
        }else{
            $book_items = $book->items()->whereBetween('order_id',[$target_order+1,$dest_id])->select();
            foreach ($book_items as $item){
                $item->setDec('order_id');
                $item->save();
            }
        }
        $targetitem->order_id=$dest_id;
        $targetitem->save();
        s('success');
    }
}