<?php

declare(strict_types=1);

namespace App\Domain\Markets\Entities;

use Spatie\DataTransferObject\DataTransferObject;

final class CoinOverview extends DataTransferObject
{
    public Coin $coin;
    public int $rank;
    public float $price;
    public ?float $priceChange24h;
    public float $marketCap;
    public float $volume;
}
