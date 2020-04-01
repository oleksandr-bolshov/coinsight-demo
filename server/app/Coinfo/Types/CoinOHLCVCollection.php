<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Spatie\DataTransferObject\DataTransferObjectCollection;

final class CoinOHLCVCollection extends DataTransferObjectCollection
{
    public function current(): CoinOHLCV
    {
        return parent::current();
    }
}
