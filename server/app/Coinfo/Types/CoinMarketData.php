<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Spatie\DataTransferObject\DataTransferObject;

final class CoinMarketData extends DataTransferObject
{
    public string $name;
    public string $symbol;
    public int $rank;
    public int $circulatingSupply;
    public int $maxSupply;
    public float $price;
    public float $volume;
    public float $volumeChange;
    public float $marketCap;
    public float $marketCapChange;
    public float $change1h;
    public float $change24h;
    public float $change7d;
    public float $change30d;
    public float $change1y;
}
