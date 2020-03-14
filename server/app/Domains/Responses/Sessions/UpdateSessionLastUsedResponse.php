<?php

declare(strict_types=1);

namespace App\Domains\Responses\Sessions;

use Spatie\DataTransferObject\DataTransferObject;

final class UpdateSessionLastUsedResponse extends DataTransferObject
{
    public int $id;
}
