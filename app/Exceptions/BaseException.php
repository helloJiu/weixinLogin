<?php
/**
 * Created by PhpStorm.
 * User: helloJiu
 * Date: 2018/2/25
 * Time: 14:53
 */

namespace App\Exceptions;
use Exception;

class BaseException extends Exception
{
    protected $message = '';

    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        if(!empty($message)){
            $this->message = $message;
        }
        parent::__construct($this->message, $code, $previous);
    }
}