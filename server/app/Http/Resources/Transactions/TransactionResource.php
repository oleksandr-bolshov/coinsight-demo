<?php

declare(strict_types=1);

namespace App\Http\Resources\Transactions;

use App\Http\Resources\Markets\CoinResource;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class TransactionResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'coin' => new CoinResource($this->coin),
            'type' => $this->type->value,
            'price_per_coin' => $this->pricePerCoin,
            'quantity' => $this->quantity,
            'fee' => $this->fee,
            'cost' => $this->cost,
            'current_value' => $this->currentValue,
            'value_change' => $this->valueChange,
            'datetime' => $this->datetime,
        ];
    }
}
