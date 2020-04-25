<?php

declare(strict_types=1);

namespace App\Http\Requests\Markets;

use App\Domain\Markets\Enums\ChartDays;
use App\Http\Requests\ApiRequest;

final class GetCoinHistoricalDataApiRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'period' => 'required|string|in:1d,1w,1m,6m,1y,all',
        ];
    }

    public function id(): int
    {
        return (int) $this->route('id');
    }

    public function period(): int
    {
        return [
            '1d' => ChartDays::ONE_DAY,
            '1w' => ChartDays::ONE_WEEK,
            '1m' => ChartDays::ONE_MONTH,
            '6m' => ChartDays::SIX_MONTH,
            '1y' => ChartDays::ONE_YEAR,
            'all' => ChartDays::MAX,
        ][$this->get('period')];
    }
}
