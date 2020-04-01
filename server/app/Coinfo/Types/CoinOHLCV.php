<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Spatie\DataTransferObject\DataTransferObject;

final class CoinOHLCV extends DataTransferObject
{
    public int $timestamp;
    public float $open;
    public float $high;
    public float $low;
    public float $close;
    public float $volume;
}
