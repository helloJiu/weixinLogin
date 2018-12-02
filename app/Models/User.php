<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class User extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'user';

    protected $casts = [
        'tagid_list' => 'array',
    ];

    /**
     * @param $weixin
     * {
     * "subscribe": 1,
     * "openid": "o6_bmjrPTlm6_2sgVt7hMZOPfL2M",
     * "nickname": "Band",
     * "sex": 1,
     * "language": "zh_CN",
     * "city": "广州",
     * "province": "广东",
     * "country": "中国",
     * "headimgurl":"http://thirdwx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0",
     * "subscribe_time": 1382694957,
     * "unionid": " o6_bmasdasdsad6_2sgVt7hMZOPfL"
     * "remark": "",
     * "groupid": 0,
     * "tagid_list":[128,2],
     * "subscribe_scene": "ADD_SCENE_QR_CODE",
     * "qr_scene": 98765,
     * "qr_scene_str": ""
     * }
     * @return static
     */
    public static function addOrUpdate($weixin){
        $user = static::getUserByOpenid($weixin->openid);
        if(is_null($user)){
            // 添加操作
            $user = new static;
            foreach($weixin as $field => $v){
                $user->{$field} = $v;
            }
            $user->save();
        }else{
            $i = 0;
            foreach($weixin as $field => $v){
                if($user->{$field} != $v){
                    $i++;
                    $user->{$field} = $v;
                }
            }
            // 只要有一处更改, 就重新保存
            if($i > 0){
                $user->save();
            }
        }

        return $user;
    }

    public static function getUserByOpenid($openid){
        return static::where('openid', $openid)->first();
    }

    public static function unSubscribe($openid){
        $user = static::getUserByOpenid($openid);
        $user->subscribe = 0;
        $user->save();
        return $user;
    }
}