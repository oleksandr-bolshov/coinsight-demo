<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Transactions;

use App\Coinfo\Client;
use App\Domain\Common\Exceptions\ModelNotFound;
use App\Domain\Portfolios\Entities\Transaction as TransactionEntity;
use App\Domain\Portfolios\Exceptions\PortfolioNotFound;
use App\Domain\Portfolios\Models\Portfolio;
use App\Domain\Portfolios\Models\Transaction;
use App\Domain\Portfolios\Services\TransactionCalculator;

final class GetTransactionsInteractor
{
    private Client $client;
    private TransactionCalculator $transactionCalculator;

    public function __construct(Client $client, TransactionCalculator $transactionCalculator)
    {
        $this->client = $client;
        $this->transactionCalculator = $transactionCalculator;
    }

    public function execute(GetTransactionsRequest $request): GetTransactionsResponse
    {
        try {
            $portfolio = Portfolio::whereId($request->portfolioId)
                ->whereUserId($request->userId)
                ->firstOrFail();
        } catch (ModelNotFound $exception) {
            throw new PortfolioNotFound();
        }

        $transactionCollection = Transaction::with('coin')->wherePortfolioId($portfolio->id)->get();

        $coinsNames = $transactionCollection->map(fn (Transaction $transaction) => $transaction->coin->name);

        $coinOverviewCollection = collect($this->client->marketsForCoins($coinsNames->toArray()));

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
