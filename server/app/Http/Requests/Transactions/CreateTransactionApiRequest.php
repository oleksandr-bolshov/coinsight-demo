<?php

declare(strict_types=1);

namespace App\Http\Requests\Transactions;

use App\Http\Requests\ApiRequest;
use App\Http\Requests\AuthContextTrait;
use Carbon\Carbon;

final class CreateTransactionApiRequest extends ApiRequest
{
    use AuthContextTrait;

    public function rules(): array
    {
        return [
            'portfolio_id' => 'required|integer|min:1',
            'coin_id' => 'required|integer|min:1',
            'type' => 'required|in:buy,sell',
            'price_per_coin' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'fee' => 'required|numeric|min:0',
            'datetime' => 'date',
        ];
    }

    public function portfolioId(): int
    {
        return (int) $this->get('portfolio_id');
    }

    public function coinId(): int
    {
        return (int) $this->get('coin_id');
    }

    public function type(): string
    {
        return $this->get('type');
    }

    public function pricePerCoin(): float
    {
        return (float) $this->get('price_per_coin');
    }

    public function quantity(): float
    {
        return (float) $this->get('quantity');
    }

    public function fee(): float
    {
        return (float) $this->get('fee');
    }

    public function datetime(): ?Carbon
    {
        return $this->has('datetime') ? Carbon::parse($this->get('datetime')) : null;
    }
}
