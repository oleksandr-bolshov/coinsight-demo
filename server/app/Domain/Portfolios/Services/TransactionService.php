<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Services;

use App\Domain\Portfolios\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

final class TransactionService
{
    public function getCollectionByPortfolioId(int $portfolioId, array $withRelations = []): Collection
    {
        return Transaction::with($withRelations)->wherePortfolioId($portfolioId)->get();
    }

    public function store(Transaction $transaction): Transaction
    {
        $transaction->save();
        return $transaction->fresh();
    }
}
