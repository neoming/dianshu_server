<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 2018/6/9
 * Time: 17:19
 */

namespace app\common\validate;


use think\Validate;

class BookCharacterValidate extends Validate
{
    protected $rule = [
        'book_id' => 'require|number',
        'name' => 'require|max:20',
        'avatar' => 'max:50',
        'character_id'=>'require|number'
    ];

    protected $message = [
        'book_id.require' => 'book_id cannot be empty',
        'book_id.number' => 'book_id must be a number',
        'name.require' => 'name cannot be empty',
        'name.max' => 'length of name cannot be over 20',
        'avatar.max' =>'length of avtar cannot be over 50',
        'character_id.require'=>'character_id can not be empty',
        'character_id.number'=> 'character_id must be a number'
    ];

    protected $scene = [
        'addCharacter' => ['book_id','name','avatar'],
        'editCharacter'=>['character_id','name','avatar'],
        'removeCharacter'=>['character_id'],
        'getCharacterList'=>['book_id']
    ];
}
