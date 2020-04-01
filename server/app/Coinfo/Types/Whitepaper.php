<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Spatie\DataTransferObject\DataTransferObject;

final class Whitepaper extends DataTransferObject
{
    public string $link;
    public string $thumbnail;
}
