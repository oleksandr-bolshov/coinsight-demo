<?php

declare(strict_types=1);

namespace App\Domains\Responses\Users;

use App\Domains\Entities\User;
use Spatie\DataTransferObject\DataTransferObject;

final class GetUserByIdResponse extends DataTransferObject
{
    public User $user;
}
