<?php

declare(strict_types=1);

namespace App\Http\Requests\Markets;

use App\Http\Requests\ApiRequest;

final class GetCoinMarketDataApiRequest extends ApiRequest
{
    public function id(): int
    {
        return (int) $this->route('id');
    }
}
