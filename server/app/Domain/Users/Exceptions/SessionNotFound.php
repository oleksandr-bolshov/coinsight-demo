<?php

declare(strict_types=1);

namespace App\Domain\Users\Exceptions;

use App\Domain\Common\Exceptions\ModelNotFound;

final class SessionNotFound extends ModelNotFound
{
    protected $message = "Session not found.";
}
