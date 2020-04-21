<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use Spatie\DataTransferObject\DataTransferObject;

final class GetPortfoliosRequest extends DataTransferObject
{
    public int $userId;
}
