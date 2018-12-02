<?php
/**
 * 认真编码 快乐生活
 * User: helloJiu
 * Date: 2018/11/27
 * Time: 16:07
 */

namespace App\Libs;

use Log;
use Cache;

class WeixinUtil
{
    private $curl = null;

    public function __construct()
    {
        $this->curl = new Curl();
    }


    // https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140183
    public function getAccessToken()
    {
        /**
         * {"access_token":"16_ulyZlWDoyRJUIGsNdoIOstoU4K_HyBEm0tikaTnvsTKGLLoQ8jlC89nrnG7HJm__qu2eJaTwyu_Sd6TlxYmSabpznhhB3hpSibBBPvQD_TWHTSL4I7Nf02Nf-JpZlO1XUq7UqUbjuQfzc33bIWWfAIAPDD","expires_in":7200}
         */
        $key = 'accessToken';
         if(Cache::has($key)){
             return Cache::get($key);
         }
        $url = sprintf(config('weixin.get_token_url'),
            config('weixin.client_id'), config('weixin.client_secret'));

        $res = $this->curl->get($url);
        if(!isset($res->access_token)){
            throw new \Exception('token获取错误');
        }
        Cache::put($key, $res->access_token, $res->expires_in/60);
        return $res->access_token;

    }

    // https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443433542
    public function getTicket(){
        /*
         * {"ticket":
         * "gQHW8DwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAycWZPUFo3X0JjcEUxMDAwMGcwM3oAAgS_SP1bAwQAAAAA",
         * "url":"http:\/\/weixin.qq.com\/q\/02qfOPZ7_BcpE10000g03z"
         * }
         */
        $accessToken = $this->getAccessToken();
        $url = sprintf(config('weixin.get_ticket_url'), $accessToken);
        // 随机生成一个$scene_id
        $max_int = pow(2, 31) - 1;
        $scene_id = rand(0, $max_int);
        /**
         * expire_seconds	该二维码有效时间，以秒为单位。 最大不超过2592000（即30天）
         *      ，此字段如果不填，则默认有效期为30秒
         * action_name	二维码类型，QR_SCENE为临时的整型参数值，QR_STR_SCENE为临时的字符串参数值，
         *      QR_LIMIT_SCENE为永久的整型参数值，QR_LIMIT_STR_SCENE为永久的字符串参数值
         * action_info	二维码详细信息
         * scene_id	场景值ID，临时二维码时为32位非0整型，永久二维码时最大值为100000（目前参数只支持1--100000）
         * scene_str	场景值ID（字符串形式的ID），字符串类型，长度限制为1到64
         * $param = '{"action_name":"QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}'
         */

        $params = [
            'action_name' => 'QR_SCENE',
            'action_info' => [
                'scene' => ['scene_id' => $scene_id]
            ]
        ];
        $data = [];
        $data['ticket'] = $this->curl->post($url, json_encode($params))->ticket;
        $data['scene_id'] = $scene_id;
        return $data;
    }

    // https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140839
    public function getWeixinUserInfo($openid){
        $accessToken = $this->getAccessToken();
        $url = sprintf(config('weixin.get_user_url'), $accessToken, $openid);
        return $this->curl->get($url);
    }



}