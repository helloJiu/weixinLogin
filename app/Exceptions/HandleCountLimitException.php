<?php
/**
 * Created by PhpStorm.
 * User: helloJiu
 * Date: 2018/2/25
 * Time: 14:54
 */

namespace App\Exceptions;


class HandleCountLimitException extends BaseException
{

    protected $message = '当天操作次数仅限xxx次, 想继续浏览吗?';
    protected $code = 50002;
}