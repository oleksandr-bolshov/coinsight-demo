<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Spatie\DataTransferObject\DataTransferObjectCollection;

final class LinkCollection extends DataTransferObjectCollection
{
    public function current(): Link
    {
        return parent::current();
    }
}
