<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Domain\Markets\Entities\CoinProfile;
use Spatie\DataTransferObject\DataTransferObject;

final class GetCoinProfileResponse extends DataTransferObject
{
    public CoinProfile $coinProfile;
}
