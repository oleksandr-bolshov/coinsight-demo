<?php

declare(strict_types=1);

namespace App\Domains\Responses\Sessions;

use Spatie\DataTransferObject\DataTransferObject;

final class TerminateSessionResponse extends DataTransferObject
{
    public int $id;
}
