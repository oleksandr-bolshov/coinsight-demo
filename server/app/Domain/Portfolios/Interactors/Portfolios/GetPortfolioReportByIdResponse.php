<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Domain\Portfolios\Entities\Report;
use Spatie\DataTransferObject\DataTransferObject;

final class GetPortfolioReportByIdResponse extends DataTransferObject
{
    public Report $report;
}
