<?php
/**
 * Created by PhpStorm.
 * User: helloJiu
 * Date: 2018/2/25
 * Time: 14:54
 */

namespace App\Exceptions;


class OpenIdException extends BaseException
{

    protected $message = 'openid获取失败';
}