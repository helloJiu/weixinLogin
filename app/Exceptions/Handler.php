<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Log;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    // public function render($request, Exception $exception)
    // {
    //     return parent::render($request, $exception);
    // }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {        if($exception instanceof AuthenticationException){
            return redirect('login');
        }
        if(config('app.debug')){
            Log::error($exception);
            if(app('request')->ajax() || app('request')->wantsJson()) {
                return $this->jsonErrWithException($exception);
            }
            return parent::render($request, $exception);
            // $e = $this->prepareException($exception);
            //
            // if ($e instanceof HttpResponseException) {
            //     return $e->getResponse();
            // } elseif ($e instanceof ValidationException) {
            //     return $this->convertValidationExceptionToResponse($e, $request);
            // }
            // $data = [
            //     'code' => $exception->getCode() ?? 503,
            //     'msg' => $exception->getMessage()
            // ];
            // return response()->view('errors.503', $data, 200);
        }else{
            // 生产环境系统异常需要记录日志
            \Log::error($exception);
            // if(!$exception instanceof BaseException){
            // }
            return parent::render($request, $exception);
            // if(app('request')->ajax() || app('request')->wantsJson()) {
            //     return $this->jsonErrWithException($exception);
            // } else {
            //     $data = [];
            //     $data['code'] = $exception->getCode() ?? 503;
            //     if($exception instanceof BaseException){
            //         $data['msg'] = $exception->getMessage();
            //     }else{
            //         $data['msg'] = '系统错误';
            //
            //     }
            //
            //     return response()->view('errors.503', $data, 200);
            // }

        }

        return $this->prepareResponse($request, $e);

    }


    public function jsonErrWithException(Exception $exception) {
        $data = [];
        // 如果不是基本异常, 并且是生产环境, 则隐藏错误信息
        if(!$exception instanceof BaseException && !config('app.debug')){
            $data['statusinfo'] = '系统错误';
        }else{
            $data['statusinfo'] = $exception->getMessage();
        }
        $data['data'] = '';
        $data['status'] = 1;
        return response()->json($data, 200);
    }
}
