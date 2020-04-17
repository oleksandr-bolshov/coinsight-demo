<?php

declare(strict_types=1);

namespace App\Domain\Markets\Entities;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

final class CoinHistoricalData extends DataTransferObject
{
    public Carbon $timestamp;
    public float $price;
    public float $marketCap;
    public ?float $volume;
}
