<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 2018/6/9
 * Time: 17:16
 */

namespace app\api\controller;


use app\common\controller\Api;
use app\common\model\Book;
use app\common\model\BookCharacter;

use app\common\model\User;
use app\common\validate\BookCharacterValidate;

class BookCharacterController extends Api
{

    /**
     * @param User $user
     * @param $book_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add(User $user,$book_id){
        $inputs = input('request.');
        $vali = new BookCharacterValidate();
        if(!$vali->scene('addCharacter')->check($inputs))
            e(1, $vali->getError());
        $book = Book::where('id',$book_id)->find();
        if(!$book)e(2,'error book id');
        if($user->id != $book->author_id)e(3,'error user id');

        $character = new BookCharacter();
        $character->allowField(['book_id','name', 'avatar'])
            ->save($inputs);
        s('success', $character);
    }

    /**
     * @param User $user
     * @param $character_id
     */
    public function edit(User $user,$character_id){
        $inputs = input('request.');
        $vali = new BookCharacterValidate();

        if(!$vali->scene('editCharacter')->check($inputs))
            e(1, $vali->getError());


        $character = BookCharacter::where('id',$character_id)->find();
        $book = $character->book;
        if(!$character)e(2,'no such character');
        if(!$book)e(3,'book not fund');
        if($user->id != $character->book->author_id)e(4,'unauthorized');

        $character->allowField(['name', 'avatar'])
            ->save($inputs);
        s("success",$character);
    }

    /**
     * @param User $user
     * @param $character_id
     */
    public function remove(User $user,$character_id){
        $inputs = input('request.');
        $vali = new BookCharacterValidate();

        if(!$vali->scene('removeCharacter')->check($inputs))
            e(1, $vali->getError());
        $character = BookCharacter::where('id',$character_id)->find();
        if(!$character)e(2,"character not found");
        if(!$character->book)e(2,'no such book');

        if($user->id != $character->book->author_id)e(3,'unauthorized');

        $character->delete();
        s("success");
    }

    /**
     * @param User $user
     * @param $book_id
     */
    public function getCharacterList(User $user,$book_id){
        $inputs = input('request.');
        $validate = new BookCharacterValidate();
        if(!$validate->scene('getCharacterList')->check($inputs))
            e(1, $validate->getError());
        $book = Book::where('id',$book_id)->find();
        if(!$book)e(2,'no such book');
        if($user->id != $book->author_id)e(3,'user_id don not match the author_id');
        $characters = BookCharacter::where('book_id','=',$book_id)->select();
        $count = $characters->count();
        if(!$count)e(4,'no characters yet');
        s("success",$characters);
    }
}
