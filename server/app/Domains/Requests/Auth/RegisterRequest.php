<?php

declare(strict_types=1);

namespace App\Domains\Requests\Auth;

use Spatie\DataTransferObject\DataTransferObject;

final class RegisterRequest extends DataTransferObject
{
    public string $username;
    public string $email;
    public string $password;
}
