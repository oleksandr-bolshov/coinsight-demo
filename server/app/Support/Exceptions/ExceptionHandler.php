<?php

declare(strict_types=1);

namespace App\Support\Exceptions;

use App\Http\Common\ApiResponse;
use App\Http\Common\Exceptions\RequestValidation;
use App\Http\Common\Exceptions\UnknownException;
use App\Support\Contracts\Exception as ExceptionContract;
use Exception;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Validation\ValidationException;
use Throwable;

final class ExceptionHandler extends Handler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return ApiResponse::error(new RequestValidation($exception->validator));
        }

        if ($exception instanceof ExceptionContract) {
            return ApiResponse::error($exception);
        }

        /*if ($exception instanceof Exception) {
            return ApiResponse::error(new UnknownException($exception->getMessage()));
        }*/

        return parent::render($request, $exception);
    }
}
