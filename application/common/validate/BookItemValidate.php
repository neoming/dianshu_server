<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-6-8
 * Time: 上午12:55
 */

namespace app\common\validate;


use think\Validate;

class BookItemValidate extends Validate
{
    protected $rule = [
        'book_id' => 'require|number',
        'order_id' => 'require|number',
        'type' => 'require|number',
        'position' => 'require|in:0,1,2',
        'character_id'=>'require|number',
        'content'=>'require|max:500',
        'dest_id'=>'number|require'
    ];

    protected $message = [
        'book_id.require' => 'book_id cannot be empty',
        'book_id.number' => 'book_id must be a number',
        'order_id.require' => 'order_id cannot be empty',
        'order_id.number' => 'order_id must be a number',
        'type.require' => 'type cannot be empty',
        'type.number' => 'type must be a number',
        'position.require' => 'position cannot be empty',
        'position.in' => 'position must in 0,1,2',
        'character_id.require' => 'character_id cannot be empty',
        'character_id.number' => 'character_id must be a number',
        'content.require' =>'content cannot be empty',
        'content.max' =>'length of content cannot be over 500',
        'dest_id.mumber'=>'dest_id must be a number',
        'dest_id.require'=>'dest_id cannot be empty'
    ];

    protected $scene = [
        'itemAdd' => ['book_id','type','position','character_id','content'],
        'itemEdit'=>['type','position','character_id','content'],
        'itemRemove'=>['id','book_id'],
        'itemMove'=>['dest_id','id']
    ];

    public function sceneRegister(){
        return $this->only(['username', 'password', 'phone'])
            ->append('username', 'unique:user')
            ->message('username.unique', 'username already exists')
            ->append('phone', 'unique:user')
            ->message('phone.unique', 'phone already exists');
    }
}