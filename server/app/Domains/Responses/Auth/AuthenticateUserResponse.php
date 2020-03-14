<?php

declare(strict_types=1);

namespace App\Domains\Responses\Auth;

use App\Domains\Entities\User;
use Spatie\DataTransferObject\DataTransferObject;

final class AuthenticateUserResponse extends DataTransferObject
{
    public User $user;
}
