<?php
/**
 * Created by PhpStorm.
 * User: helloJiu
 * Date: 2018/2/25
 * Time: 14:54
 */

namespace App\Exceptions;


class TooManyAttemptException extends BaseException
{

    protected $message = '请不要频繁请求';
}