<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Exceptions;

use App\Domain\Common\Exceptions\ModelNotFound;

final class PortfolioNotFound extends ModelNotFound
{
    protected $message = "Portfolio not found.";
}
