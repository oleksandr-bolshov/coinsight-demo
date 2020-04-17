<?php

declare(strict_types=1);

namespace App\Domain\Markets\Entities;

use Spatie\DataTransferObject\DataTransferObject;

final class CoinMarketData extends DataTransferObject
{
    public int $id;
    public string $name;
    public string $symbol;
    public int $rank;
    public float $circulatingSupply;
    public float $maxSupply;
    public float $price;
    public float $volume;
    public float $volumeChange24h;
    public float $marketCap;
    public float $marketCapChange24h;
    public float $priceChange1h;
    public float $priceChange24h;
    public float $priceChange7d;
    public float $priceChange30d;
    public float $priceChange1y;
}
