<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Spatie\DataTransferObject\DataTransferObject;

final class Link extends DataTransferObject
{
    public string $type;
    public string $url;
}
