<?php

declare(strict_types=1);

namespace App\Domains\Responses\Sessions;

use App\Domains\Entities\Session;
use Spatie\DataTransferObject\DataTransferObject;

final class CreateSessionResponse extends DataTransferObject
{
    public Session $session;
}
