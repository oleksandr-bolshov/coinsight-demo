<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Spatie\DataTransferObject\DataTransferObject;

final class GlobalStats extends DataTransferObject
{
    public float $marketCap;
    public float $marketCapChange;
    public float $volume;
    public float $volumeChange;
    public float $bitcoinDominance;
}
