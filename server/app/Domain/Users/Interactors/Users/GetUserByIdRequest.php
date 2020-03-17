<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Users;

use Spatie\DataTransferObject\DataTransferObject;

final class GetUserByIdRequest extends DataTransferObject
{
    public int $id;
}
