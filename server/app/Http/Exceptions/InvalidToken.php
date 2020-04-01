<?php

declare(strict_types=1);

namespace App\Http\Exceptions;

use App\Support\Contracts\Exception as ExceptionContract;
use App\Support\Traits\ExceptionToArrayTrait;
use Exception;
use Illuminate\Http\Response;
use Throwable;

final class InvalidToken extends Exception implements ExceptionContract
{
    use ExceptionToArrayTrait;

    protected $message = "JWT token is invalid.";

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if ($message !== "") {
            $this->message = $message;
        }

        parent::__construct($this->message, $code, $previous);
    }

    public function getStatus(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }
}
