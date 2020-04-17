<?php

declare(strict_types=1);

namespace App\Domain\Markets\Enums;

use BenSampo\Enum\Enum;

final class ChartDays extends Enum
{
    const ONE_DAY = 1;
    const ONE_WEEK = 7;
    const ONE_MONTH = 30;
    const SIX_MONTH = 180;
    const ONE_YEAR = 365;
    const MAX = 'max';
}
