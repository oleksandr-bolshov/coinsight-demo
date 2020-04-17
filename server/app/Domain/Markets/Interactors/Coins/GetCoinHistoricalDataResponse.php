<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

final class GetCoinHistoricalDataResponse extends DataTransferObject
{
    public Collection $coinHistoricalData;
}
