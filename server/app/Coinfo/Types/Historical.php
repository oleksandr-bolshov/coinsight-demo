<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

final class Historical extends DataTransferObject
{
    public Carbon $timestamp;
    public float $price;
    public float $volume;
    public float $marketCap;
}
