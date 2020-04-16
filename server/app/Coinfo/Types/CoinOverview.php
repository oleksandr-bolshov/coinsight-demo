<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Spatie\DataTransferObject\DataTransferObject;

final class CoinOverview extends DataTransferObject
{
    public string $name;
    public string $symbol;
    public string $icon;
    public int $rank;
    public ?float $price;
    public ?float $priceChange24h;
    public float $marketCap;
    public float $volume;
}
