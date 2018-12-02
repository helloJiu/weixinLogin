<?php
/**
 * 认真编码 快乐生活
 * User: helloJiu
 * Date: 2018/7/25
 * Time: 18:07
 */

namespace App\Exceptions;


class IllegalParamException extends BaseException
{
    protected $message = '无效参数';
}