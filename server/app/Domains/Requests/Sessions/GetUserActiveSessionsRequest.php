<?php

declare(strict_types=1);

namespace App\Domains\Requests\Sessions;

use App\Domains\Contracts\PaginationRequest;
use Spatie\DataTransferObject\DataTransferObject;

final class GetUserActiveSessionsRequest extends DataTransferObject implements PaginationRequest
{
    public int $id;
    public int $page = self::DEFAULT_PAGE;
    public int $perPage = self::DEFAULT_PER_PAGE;
    public string $sort = self::DEFAULT_SORT;
    public string $direction = self::DEFAULT_DIRECTION;
}
