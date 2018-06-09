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
use app\common\model\User;
use app\common\validate\BookCharacterValidate;

class BookCharacterController extends Api
{


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


    public function edit(User $user,$character_id,$book_id){
        $inputs = input('request.');
        $vali = new BookCharacterValidate();

        if(!$vali->scene('editCharacter')->check($inputs))
            e(1, $vali->getError());

        $book = Book::where('id',$book_id)->find();
        if(!$book)e(2,'no such book');

        $character = BookCharacter::where('id',$character_id)->find();
        if(!$character)e(3,'no such character');

        if($user->id != $book->author_id)e(3,'user_id don not match the author_id');

        $character->allowField(['name', 'avatar'])
            ->save($inputs);
        s("success");
    }


    public function remove(User $user,$book_id,$character_id){
        $inputs = input('request.');
        $vali = new BookCharacterValidate();

        if(!$vali->scene('removeCharacter')->check($inputs))
            e(1, $vali->getError());

        $book = Book::where('id',$book_id)->find();
        if(!$book)e(2,'no such book');

        $character = BookCharacter::where('id',$character_id)->find();
        if(!$character)e(3,'no such character');

        if($user->id != $book->author_id)e(3,'user_id don not match the author_id');

        $character->delet();
        s("success");
    }

}