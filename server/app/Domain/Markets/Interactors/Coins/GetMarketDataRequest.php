<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use Spatie\DataTransferObject\DataTransferObject;

final class GetMarketDataRequest extends DataTransferObject
{
    public int $id;
}
