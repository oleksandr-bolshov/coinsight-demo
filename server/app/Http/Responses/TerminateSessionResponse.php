<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Spatie\DataTransferObject\DataTransferObject;

final class TerminateSessionResponse extends DataTransferObject
{
    public int $id;
}
