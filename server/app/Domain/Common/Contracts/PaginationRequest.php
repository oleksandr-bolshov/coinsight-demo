<?php

declare(strict_types=1);

namespace App\Domain\Common\Contracts;

use App\Domain\Common\Enums\SortBy;
use App\Domain\Common\Enums\SortDirection;

interface PaginationRequest
{
    public const DEFAULT_PAGE = 1;
    public const DEFAULT_PER_PAGE = 15;
    public const DEFAULT_SORT = SortBy::CREATED_AT;
    public const DEFAULT_DIRECTION = SortDirection::DESC;
}
