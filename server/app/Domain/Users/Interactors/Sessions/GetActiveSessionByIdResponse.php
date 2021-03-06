<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Sessions;

use App\Domain\Users\Entities\Session;
use Spatie\DataTransferObject\DataTransferObject;

final class GetActiveSessionByIdResponse extends DataTransferObject
{
    public Session $session;
}
