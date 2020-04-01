<?php

declare(strict_types=1);

namespace App\Http\Exceptions;

use App\Support\Contracts\Exception as ExceptionContract;
use App\Support\Traits\ExceptionToArrayTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;

final class TokenRequired extends AuthenticationException implements ExceptionContract
{
    use ExceptionToArrayTrait;

    protected $message = "JWT token is required.";

    public function __construct(string $message = '', array $guards = [], ?string $redirectTo = null)
    {
        parent::__construct($this->message, $guards, $redirectTo);
    }

    public function getStatus(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }
}
