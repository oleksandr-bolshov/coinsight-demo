<?php

declare(strict_types=1);

namespace App\Domains\Exceptions;

final class UserNotFound extends ModelNotFound
{
    protected $message = "User not found.";
}
