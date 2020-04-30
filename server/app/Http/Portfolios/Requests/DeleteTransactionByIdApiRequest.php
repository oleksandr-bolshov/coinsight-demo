<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Requests;

use App\Http\Common\Requests\ApiRequest;
use App\Http\Common\Requests\AuthContextTrait;

final class DeleteTransactionByIdApiRequest extends ApiRequest
{
    use AuthContextTrait;

    public function rules(): array
    {
        return [
            'portfolio_id' => 'required|integer',
        ];
    }

    public function id(): int
    {
        return (int) $this->route('id');
    }

    public function portfolioId(): int
    {
        return (int) $this->get('portfolio_id');
    }
}
