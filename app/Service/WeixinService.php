<?php

namespace App\Service;
use App\Exceptions\InternalSocketConnectException;
use App\Exceptions\OpenIdException;
use Log;
use App\Models\User;


/**
 * 认真编码 快乐生活
 * User: helloJiu
 * Date: 2018/11/27
 * Time: 16:04
 */
class WeixinService
{
    /**
     * 处理微信各种事件
     * https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140454
     * @param $postObject
     * @return array|string
     */
    public function handleEvent($postObject){
        $content = '';
        switch ($postObject->Event) {

            // 订阅事件
            case "subscribe":
                // 不trim可能会发生不可描述的错误.惨..
                $fromUserName = trim($postObject->FromUserName);
                $scene = trim($postObject->EventKey);
                $scene = ltrim($scene, 'qrscene_');
                $this->handleMessage($fromUserName, $scene);
                $content = 'hello, 欢迎使用发标啦!';
                break;

            // 取消订阅事件
            case "unsubscribe":
                $content = '取消订阅成功!';
                $fromUserName = trim($postObject->FromUserName);
                $this->unSubscribe($fromUserName);
                break;

            // 点击菜单事件
            case "CLICK":
                $key = $postObject->EventKey;
                $content = $this->menuResponse($key);
                break;

            // 已关注, 重新扫码时间
            case "SCAN":
                $content = '欢迎使用发标啦!';
                $fromUserName = trim($postObject->FromUserName);
                $scene = trim($postObject->EventKey);
                $this->handleMessage($fromUserName, $scene);
                break;

        }
        return $content;
    }

    /**
     * 取消订阅
     * @param $openid
     */
    public function unSubscribe($openid){
        User::unSubscribe($openid);
    }

    /**
     * 公众号菜单响应
     * @param $key
     * @return array|string
     */
    public function menuResponse($key)
    {
        if ($key == '') {
            $content = array(
                array(
                    'Title' => '',
                    'Description' => '',
                    'PicUrl' => '',
                    'Url' => '',
                ),
            );
        } else if ($key == '') {
            //点击的是关于我们
            $content = "我是发标啦";
        }
        return $content;
    }


    /**
     * 处理用户信息并且向workerman发送用户是否登录的消息
     * @param $openid
     * @param $scene
     * @throws OpenIdException
     */
    public function handleMessage($openid, $scene)
    {
        // 使用前, 需要把$openid和$scene trim处理, 不然报错都不知道啥原因
        if (empty($openid) || empty($scene)) {
            throw new OpenIdException();
        }

        $weixin_user = app('App\Libs\WeixinUtil')->getWeixinUserInfo($openid);
        User::addOrUpdate($weixin_user);

        $token = AuthService::getToken($openid);

        // 使用scene标识到底是哪一个客户端(浏览器)
        $req = ['scene' => $scene, 'token' => $token];
        $this->socket(json_encode($req));
    }


    /**
     * 向websocket服务推送用户openid和scene_id
     * websocket服务在想浏览器进行推送
     * @param $req
     * @throws InternalSocketConnectException
     */
    public function socket($req)
    {
        $ip = config('bid.websocket_server_ip');
        $port = config('bid.tcp_server_internal_port');
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $result = socket_connect($socket, $ip, $port);
        if ($result < 0) {
            throw new InternalSocketConnectException();
        }

        socket_write($socket, $req, 8192);
        $out = socket_read($socket, 8192);
        if ($out == 'fail') {
            Log::alert('向浏览器推送信息失败....');
            Log::alert('失败信息: ' . $req);
        }
        socket_close($socket);
    }

}