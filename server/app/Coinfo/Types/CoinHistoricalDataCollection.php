<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Spatie\DataTransferObject\DataTransferObjectCollection;

final class CoinHistoricalDataCollection extends DataTransferObjectCollection
{
    public function current(): CoinHistoricalData
    {
        return parent::current();
    }
}
