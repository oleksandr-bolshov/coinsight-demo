<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Transactions;

use App\Coinfo\Client;
use App\Domain\Portfolios\Entities\Transaction as TransactionEntity;
use App\Domain\Portfolios\Models\Transaction;
use App\Domain\Portfolios\Services\PortfolioService;
use App\Domain\Portfolios\Services\TransactionCalculator;
use App\Domain\Portfolios\Services\TransactionService;

final class GetTransactionsInteractor
{
    private Client $client;
    private TransactionCalculator $transactionCalculator;
    private PortfolioService $portfolioService;
    private TransactionService $transactionService;

    public function __construct(
        Client $client,
        TransactionCalculator $transactionCalculator,
        PortfolioService $portfolioService,
        TransactionService $transactionService
    ) {
        $this->client = $client;
        $this->transactionCalculator = $transactionCalculator;
        $this->portfolioService = $portfolioService;
        $this->transactionService = $transactionService;
    }

    public function execute(GetTransactionsRequest $request): GetTransactionsResponse
    {
        $portfolio = $this->portfolioService->getByIdAndUserId($request->portfolioId, $request->userId);
        $transactionCollection = $this->transactionService->getCollectionByPortfolioId($portfolio->id, ['coin']);

        $coinsNames = $transactionCollection
            ->map(fn (Transaction $transaction) => $transaction->coin->name)
            ->toArray();
        $coinOverviewCollection = collect($this->client->marketsForCoins($coinsNames));

        $transactionEntityCollection = $transactionCollection->map(
            function (Transaction $transaction) use ($coinOverviewCollection) {
                $coinOverview = $coinOverviewCollection->firstWhere('name', $transaction->coin->name);

                $cost = $this->transactionCalculator->cost(
                    $transaction->quantity, $transaction->price_per_coin, $transaction->fee
                );
                $currentValue = $this->transactionCalculator->value(
                    $transaction->quantity, $coinOverview->price
                );
                $valueChange = $this->transactionCalculator->valueChange($currentValue, $cost);

                return TransactionEntity::create($transaction, $cost, $currentValue, $valueChange);
            }
        );

        return new GetTransactionsResponse([
            'transactions' => $transactionEntityCollection,
        ]);
    }
}
