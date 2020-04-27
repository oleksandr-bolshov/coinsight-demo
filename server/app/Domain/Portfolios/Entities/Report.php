<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Entities;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

final class Report extends DataTransferObject
{
    public Portfolio $portfolio;
    public float $totalValue;
    public float $totalValueChange;
    public Collection $assets;
}
