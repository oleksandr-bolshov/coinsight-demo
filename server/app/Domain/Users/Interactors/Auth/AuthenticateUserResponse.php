<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Auth;

use App\Domain\Users\Entities\User;
use Spatie\DataTransferObject\DataTransferObject;

final class AuthenticateUserResponse extends DataTransferObject
{
    public User $user;
}
