<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Users;

use App\Domain\Users\Entities\User;
use Spatie\DataTransferObject\DataTransferObject;

final class GetUserByIdResponse extends DataTransferObject
{
    public User $user;
}
