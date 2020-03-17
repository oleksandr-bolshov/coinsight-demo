<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Auth;

use Spatie\DataTransferObject\DataTransferObject;

final class AuthenticateUserRequest extends DataTransferObject
{
    public string $username;
    public string $password;
}
