<?php
/**
 * Created by PhpStorm.
 * User: helloJiu
 * Date: 2018/2/25
 * Time: 14:54
 */

namespace App\Exceptions;


class HandleRateHighException extends BaseException
{

    protected $message = '操作太快了, 您是机器人吗?';
    protected $code = 50001;
}