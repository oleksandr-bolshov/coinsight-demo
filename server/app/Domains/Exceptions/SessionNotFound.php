<?php

declare(strict_types=1);

namespace App\Domains\Exceptions;

final class SessionNotFound extends ModelNotFound
{
    protected $message = "Session not found.";
}
