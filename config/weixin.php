<?php
/**
 * 认真编码 快乐生活
 * User: helloJiu
 * Date: 2018/11/27
 * Time: 16:10
 */

$weixin = 'https://api.weixin.qq.com';
return [
    'client_id' => env('WEIXIN_APPID'),
    'client_secret' => env('WEIXIN_SECRET'),
    'get_token_url' => $weixin . '/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
    'get_ticket_url' => $weixin . '/cgi-bin/qrcode/create?access_token=%s',
    'get_user_url' => $weixin . '/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN',
    'token' => env('WEIXIN_TOKEN', 'helloworld'),
];