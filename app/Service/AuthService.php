<?php
/**
 * Created by helloJiu.
 * Idea: 认真编码 快乐生活
 * Date: 2018/11/28
 * Time: 20:43
 */

namespace App\Service;


use App\Models\User;
use Cache;

class AuthService
{
    public static function login(User $user){
        $session_user = static::getAuthUserInfo($user);
        session()->put('openid', $user->openid);
        session()->put('user', $session_user);
        return $session_user;
    }

    public static function logout(){
        $request = app('request');
        $request->session()->flush();
    }


    public static function getToken($openid){
        $token = static::generateToken($openid);
        // 将token保存到缓存中1分钟
        Cache::put($token, $openid, 3);
        return $token;
    }

    // 生成令牌
    private static function generateToken($openid)
    {
        $randChar = getRandChar(32);
        $timestamp = time();
        $key = config('app.key');
        $openid = $openid;
        return md5($randChar . $timestamp . $key . $openid);
    }
}