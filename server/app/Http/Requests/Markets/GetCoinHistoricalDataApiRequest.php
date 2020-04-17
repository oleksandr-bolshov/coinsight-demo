<?php

declare(strict_types=1);

namespace App\Http\Requests\Markets;

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

    public function period(): string
    {
        return $this->get('period');
    }
}
