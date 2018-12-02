<?php
/**
 * Created by PhpStorm.
 * User: helloJiu
 * Date: 2018/2/25
 * Time: 14:54
 */

namespace App\Exceptions;


class InternalSocketConnectException extends BaseException
{

    protected $message = 'socket连接失败';
}