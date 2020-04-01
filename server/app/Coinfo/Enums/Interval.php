<?php

declare(strict_types=1);

namespace App\Coinfo\Enums;

use BenSampo\Enum\Enum;

final class Interval extends Enum
{
    const FIVE_MINUTES = "5m";
    const FIFTEEN_MINUTES = "15m";
    const THIRTY_MINUTES = "30m";
    const ONE_HOUR = "1h";
    const ONE_DAY = "1d";
    const ONE_WEEK = "1w";
}
