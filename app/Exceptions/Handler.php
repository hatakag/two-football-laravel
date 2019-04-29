<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
        if ($exception instanceof UnauthorizedHttpException) {
            $preException = $exception->getPrevious();
            if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(config('constants.error_response.EXPIRED_TOKEN'), Response::HTTP_BAD_REQUEST);
            } else if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(config('constants.error_response.INVALID_TOKEN'), Response::HTTP_BAD_REQUEST);
            } else if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                return response()->json(config('constants.error_response.BLACKLISTED_TOKEN'), Response::HTTP_BAD_REQUEST);
            }
            if ($exception->getMessage() === 'Token not provided') {
                return response()->json(config('constants.error_response.TOKEN_NOT_PROVIDED'), Response::HTTP_BAD_REQUEST);
            }
        }
        return parent::render($request, $exception);
    }
}
