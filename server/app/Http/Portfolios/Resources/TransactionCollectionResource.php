<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Resources;

use App\Domain\Portfolios\Entities\Transaction;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class TransactionCollectionResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        $transactions = $this->map(
            fn(Transaction $transaction) => new TransactionResource($transaction)
        );

        return [
            'transactions' => $transactions,
        ];
    }
}
