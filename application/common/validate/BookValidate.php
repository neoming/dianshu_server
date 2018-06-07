<?php
/**
 * Created by PhpStorm.
 * User: Dizy
 * Date: 2018/6/6
 * Time: 2:42
 */

namespace app\common\validate;


use think\Validate;

class BookValidate extends Validate
{

    public function __construct(array $rules = [], array $message = [], array $field = [])
    {
        $this->rule = [
            'title' => 'require|length:1,32',
            'type' => ['require', 'in' => config('dianshu.book_types')],
            'desc' => 'require|max:500',
            'cover_img' => 'max:100'
        ];
        parent::__construct($rules, $message, $field);
    }

    protected $message = [
        'title.require' => 'title cannot be empty',
        'title.length' => 'length of title could only be 1-32',
        'type.require' => 'type cannot be empty',
        'type.in' => 'invalid type',
        'desc.require' => 'desc cannot be empty',
        'desc.max' => 'length of desc cannot be over 500',
        'cover_img' => 'length of cover_img cannot be over 100',
    ];

    protected $scene = [
        'add' => ['title', 'type', 'desc', 'cover_img'],
    ];

    public function sceneEditInfo(){
        return $this->only(['title', 'type', 'desc', 'cover_img'])
            ->remove('title', 'require')
            ->remove('type', 'require')
            ->remove('desc', 'require');
    }

}