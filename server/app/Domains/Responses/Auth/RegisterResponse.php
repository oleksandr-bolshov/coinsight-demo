<?php

declare(strict_types=1);

namespace App\Domains\Responses\Auth;

use Spatie\DataTransferObject\DataTransferObject;

final class RegisterResponse extends DataTransferObject
{
    public int $id;
}
