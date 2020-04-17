<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Domain\Markets\Enums\ChartDays;
use Spatie\DataTransferObject\DataTransferObject;

final class GetCoinHistoricalDataRequest extends DataTransferObject
{
    public int $id;
    public ChartDays $days;
}
