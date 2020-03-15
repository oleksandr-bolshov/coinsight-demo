<?php

declare(strict_types=1);

namespace App\Domains\Responses\Sessions;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

final class GetUserActiveSessionsResponse extends DataTransferObject
{
    public Collection $sessions;
    public int $total;
    public int $page;
    public int $perPage;
    public int $lastPage;
}
