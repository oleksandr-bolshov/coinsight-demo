<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Auth;

use Spatie\DataTransferObject\DataTransferObject;

final class RegisterResponse extends DataTransferObject
{
    public int $id;
}
