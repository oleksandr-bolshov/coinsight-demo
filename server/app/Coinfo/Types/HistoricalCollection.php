<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Spatie\DataTransferObject\DataTransferObjectCollection;

final class HistoricalCollection extends DataTransferObjectCollection
{
    public function current(): Historical
    {
        return parent::current();
    }
}
