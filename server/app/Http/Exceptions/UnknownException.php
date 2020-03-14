<?php

declare(strict_types=1);

namespace App\Http\Exceptions;

use App\Support\Contracts\ExceptionContract;
use App\Support\Traits\ExceptionToArrayTrait;
use Exception;
use Illuminate\Http\Response;
use Throwable;

final class UnknownException extends Exception implements ExceptionContract
{
    use ExceptionToArrayTrait;

    protected $message = "Unknown exception raised.";

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if ($message !== "") {
            $this->message = $message;
        }

        parent::__construct($this->message, $code, $previous);
    }

    public function getStatus(): int
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
