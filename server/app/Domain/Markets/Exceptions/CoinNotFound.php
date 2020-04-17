<?php

declare(strict_types=1);

namespace App\Domain\Markets\Exceptions;

use App\Domain\Common\Exceptions\ModelNotFound;

final class CoinNotFound extends ModelNotFound
{
    protected $message = "Coin not found.";
}
