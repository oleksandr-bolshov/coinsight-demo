<?php

declare(strict_types=1);

namespace App\Domain\Users\Exceptions;

use App\Support\Contracts\ExceptionContract;
use App\Support\Traits\ExceptionToArrayTrait;
use Exception;
use Illuminate\Http\Response;
use Throwable;

final class WrongPassword extends Exception implements ExceptionContract
{
    use ExceptionToArrayTrait;

    protected $message = "Wrong password.";

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($this->message, $code, $previous);
    }

    public function getStatus(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }
}
