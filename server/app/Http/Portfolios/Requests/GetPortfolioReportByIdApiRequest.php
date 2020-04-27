<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Requests;

use App\Http\Common\Requests\ApiRequest;
use App\Http\Common\Requests\AuthContextTrait;

final class GetPortfolioReportByIdApiRequest extends ApiRequest
{
    use AuthContextTrait;

    public function id(): int
    {
        return (int) $this->route('id');
    }
}
