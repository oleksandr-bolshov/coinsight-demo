<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Auth;

use Spatie\DataTransferObject\DataTransferObject;

final class RegisterRequest extends DataTransferObject
{
    public string $username;
    public string $email;
    public string $password;
}
