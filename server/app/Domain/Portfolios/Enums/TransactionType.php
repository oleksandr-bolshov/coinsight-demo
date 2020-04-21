<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Enums;

use BenSampo\Enum\Enum;

final class TransactionType extends Enum
{
    const BUY = 'buy';
    const SELL = 'sell';
}
