<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\GlobalStats;

use App\Domain\Markets\Entities\GlobalStats;
use Spatie\DataTransferObject\DataTransferObject;

final class GetGlobalStatsResponse extends DataTransferObject
{
    public GlobalStats $globalStats;
}
