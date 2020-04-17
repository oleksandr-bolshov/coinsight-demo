<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use Spatie\DataTransferObject\DataTransferObject;

final class GetCoinProfileRequest extends DataTransferObject
{
    public int $id;
}
