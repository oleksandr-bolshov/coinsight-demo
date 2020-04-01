<?php

declare(strict_types=1);

namespace App\Http\Exceptions;

use App\Support\Contracts\Exception as ExceptionContract;
use App\Support\Traits\ExceptionToArrayTrait;
use Exception;
use Illuminate\Http\Response;
use Throwable;

final class InvalidSessionId extends Exception implements ExceptionContract
{
    use ExceptionToArrayTrait;

    protected $message = "Invalid session id.";

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($this->message, $code, $previous);
    }

    public function getStatus(): int
    {
        return Response::HTTP_FORBIDDEN;
    }
}
