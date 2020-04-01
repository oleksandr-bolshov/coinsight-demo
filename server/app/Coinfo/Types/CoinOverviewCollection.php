<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Spatie\DataTransferObject\DataTransferObjectCollection;

final class CoinOverviewCollection extends DataTransferObjectCollection
{
    public function current(): CoinOverview
    {
        return parent::current();
    }
}
