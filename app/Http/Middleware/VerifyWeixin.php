<?php

namespace App\Http\Middleware;

use App\Exceptions\IllegalWeixinRequest;
use Closure;
use Log;

class VerifyWeixin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 获取到微信请求里包含的几项内容
        Log::alert($request->all());
        $signature = $request->input('signature');
        $timestamp = $request->input('timestamp');
        $nonce     = $request->input('nonce');

        $token = config('weixin.token');

        // 加工出自己的 signature
        $our_signature = array($token, $timestamp, $nonce);
        sort($our_signature, SORT_STRING);
        $our_signature = implode($our_signature);
        $our_signature = sha1($our_signature);
        Log::alert($our_signature);

        // 用自己的 signature 去跟请求里的 signature 对比
        if ($our_signature != $signature) {
            throw new IllegalWeixinRequest();
        }

        return $next($request);
    }
}
