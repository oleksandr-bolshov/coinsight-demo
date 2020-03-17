<?php

declare(strict_types=1);

namespace App\Domain\Users\Exceptions;

use App\Domain\Common\Exceptions\ModelNotFound;

final class UserNotFound extends ModelNotFound
{
    protected $message = "User not found.";
}
