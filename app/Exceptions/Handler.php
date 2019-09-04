<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



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
    public function render($request, Exception $exception)
    {
        // 参数验证错误的异常，我们需要返回 400 的 http code 和一句错误信息
        if ($exception instanceof ValidationException) {
            return response(['errorCode' => 400, 'data' => ['message' => array_first(array_collapse($exception->errors()))]]);

        }
        // 用户认证的异常，我们需要返回 401 的 http code 和错误信息
        if ($exception instanceof UnauthorizedHttpException) {
            return response(['errorCode' => 401, 'data' => ['message' => '登录已过期，请重新登录']]);
        }

        //如果不被允许的路由
        if ($exception instanceof MethodNotAllowedHttpException || $exception instanceof NotFoundHttpException) {
//            if (!($request->ajax() || $request->wantsJson())) {
                return response(['errorCode' => 404, 'data' => ['message' => '请求接口不存在']]);
//            }
        }

        //判断是否存在异常
        if ($exception->getCode() == 0) {
            $errTemplate = "EXCEPTION::". $exception->getMessage(). "\r\nERRCODE::". $exception->getCode(). "\r\nFILE::". $exception->getFile(). "::LINE::". $exception->getLine();
            return response(['errorCode' => 500, 'data' => ['message' => $errTemplate]]);
        }
        return parent::render($request, $exception);
    }
}
