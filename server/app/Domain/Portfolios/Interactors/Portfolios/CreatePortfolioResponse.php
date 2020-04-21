<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Domain\Portfolios\Entities\Portfolio;
use Spatie\DataTransferObject\DataTransferObject;

final class CreatePortfolioResponse extends DataTransferObject
{
    public Portfolio $portfolio;
}
