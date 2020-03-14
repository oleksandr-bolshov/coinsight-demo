<?php

declare(strict_types=1);

namespace App\Domains\Requests\Sessions;

use Spatie\DataTransferObject\DataTransferObject;

final class UpdateSessionLastUsedRequest extends DataTransferObject
{
    public int $id;
}
