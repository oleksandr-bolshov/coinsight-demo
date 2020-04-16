<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Spatie\DataTransferObject\DataTransferObjectCollection;

final class CoinPriceByTimeCollection extends DataTransferObjectCollection
{
    public function current(): CoinPriceByTime
    {
        return parent::current();
    }
}
