<?php

declare(strict_types=1);

namespace App\Domains\Requests\Users;

use Spatie\DataTransferObject\DataTransferObject;

final class GetUserByIdRequest extends DataTransferObject
{
    public int $id;
}
