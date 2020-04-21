<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use Spatie\DataTransferObject\DataTransferObject;

final class CreatePortfolioRequest extends DataTransferObject
{
    public string $name;
    public int $userId;
}
