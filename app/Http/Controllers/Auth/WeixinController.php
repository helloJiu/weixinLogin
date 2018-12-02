<?php
/**
 * 认真编码 快乐生活
 * User: helloJiu
 * Date: 2018/11/27
 * Time: 13:16
 */

namespace App\Http\Controllers\Auth;

use App\Exceptions\IllegalParamException;
use App\Http\Controllers\Controller;
use App\Libs\WeixinUtil;
use App\Models\User;
use App\Service\AuthService;
use App\Service\WeixinService;
use Auth;
use Illuminate\Http\Request;
use Log;
use Cache;


class WeixinController extends Controller
{

    /**
     * 向微信服务器获取ticket, 发给前端, 用于生成公众号二维码
     * @param WeixinUtil $util
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTicket(WeixinUtil $util)
    {
        return $this->jsonResOk($util->getTicket());
    }

    /**
     * 验证微信公众平台token
     * @param Request $request
     * @return array|string
     */
    public function authToken(Request $request)
    {
        return $request->input('echostr');
    }


    /**
     * https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140454
     * 处理微信POST请求
     * 各种事件. 输入输出等
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function acceptMessage()
    {
        $postObj = $this->parseInput();
        Log::alert(json_encode($postObj));
        // trim重要
        $post_type = trim($postObj->MsgType);
        if ($post_type == 'event') {
            return $this->receiveEvent($postObj);
        } else {
            return $this->receiveInput($postObj);
        }

    }


    /**
     * 解析微信数据
     * @return \SimpleXMLElement
     */
    private function parseInput(){
        $message = file_get_contents('php://input');
        return simplexml_load_string($message, 'SimpleXMLElement', LIBXML_NOCDATA);
    }

    /**
     * 处理微信输入请求
     * @param $postObject
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function receiveInput($postObject)
    {
        $content = $postObject->Content;
        return view('weixin.text', compact('postObject', 'content'));
    }

    /**
     * 处理微信事件请求
     * @param $postObject
     * 参考博客: https://ninghao.net/blog/1442
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function receiveEvent($postObject)
    {
        $service = new WeixinService();
        $content = $service->handleEvent($postObject);
        $blade_name = is_array($content) ? 'weixin.news' : 'weixin.text';
        return view($blade_name, compact('postObject', 'content'));
    }


    public function login(Request $request){
        $token = $request->input('token');
        if(empty($token)){
            throw new IllegalParamException();
        }
        $user = User::where('openid', Cache::get($token))->first();
        $session_user = AuthService::login($user);
        return $this->jsonResOk($session_user);
    }

    public function logout(){
        AuthService::logout();
        return $this->jsonResOk();

    }



}
