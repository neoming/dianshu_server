<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function s($msg='success', $data = null){
    throw new think\exception\HttpResponseException(json([
        "code"=>200,
        "msg" => mlang($msg),
        "data" => $data
    ]));
}

function e($code = 0, $msg = 'failure', $data = null){
    throw new think\exception\HttpResponseException(json(
        ["code"=>400+$code,
            "msg" => mlang($msg),
            "data" => $data
        ]));
}

function mlang($str){
    return is_null($str)?null:lang($str);
}

/**
 * @param string $password
 * @return string
 */
function pwHash($password){
    return password_hash($password, PASSWORD_DEFAULT );
}

/**
 * @param string $password
 * @param string $hash
 * @return bool
 */
function pwMatch($password, $hash){
    return password_verify($password, $hash);
}

function rand_str($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = "";
    for ( $i = 0; $i < $length; $i++ )
    {
        $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    return $password;
}

