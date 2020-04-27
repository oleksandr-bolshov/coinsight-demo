<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Entities;

use App\Domain\Markets\Entities\Coin;
use Spatie\DataTransferObject\DataTransferObject;

final class Asset extends DataTransferObject
{
    public Coin $coin;
    public float $price;
    public float $priceChange24h;
    public float $holdings;
    public float $marketValue;
    public float $netCost;
    public float $netProfit;
    public float $valueChange;
    public ?float $share;
}
