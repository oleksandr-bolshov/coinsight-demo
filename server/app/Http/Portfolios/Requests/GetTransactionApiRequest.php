<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Requests;

use App\Http\Common\Requests\ApiRequest;
use App\Http\Common\Requests\AuthContextTrait;

final class GetTransactionApiRequest extends ApiRequest
{
    use AuthContextTrait;

    public function rules(): array
    {
        return [
            'portfolio_id' => 'required|integer|min:1',
        ];
    }

    public function portfolioId(): int
    {
        return (int) $this->get('portfolio_id');
    }
}
