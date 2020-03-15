<?php

declare(strict_types=1);

namespace App\Domains\Contracts;

use App\Domains\Enums\SortBy;
use App\Domains\Enums\SortDirection;

interface PaginationRequest
{
    public const DEFAULT_PAGE = 1;
    public const DEFAULT_PER_PAGE = 15;
    public const DEFAULT_SORT = SortBy::CREATED_AT;
    public const DEFAULT_DIRECTION = SortDirection::DESC;
}
