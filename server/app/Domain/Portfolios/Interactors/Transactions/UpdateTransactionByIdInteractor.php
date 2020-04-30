<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Transactions;

use App\Coinfo\Client;
use App\Domain\Markets\Services\CoinService;
use App\Domain\Portfolios\Entities\Transaction as TransactionEntity;
use App\Domain\Portfolios\Models\Transaction;
use App\Domain\Portfolios\Services\FinanceCalculator;
use App\Domain\Portfolios\Services\PortfolioService;
use App\Domain\Portfolios\Services\TransactionService;

final class UpdateTransactionByIdInteractor
{
    private Client $client;
    private FinanceCalculator $calculator;
    private PortfolioService $portfolioService;
    private CoinService $coinService;
    private TransactionService $transactionService;

    public function __construct(
        Client $client,
        FinanceCalculator $financeCalculator,
        PortfolioService $portfolioService,
        CoinService $coinService,
        TransactionService $transactionService
    ) {
        $this->client = $client;
        $this->calculator = $financeCalculator;
        $this->portfolioService = $portfolioService;
        $this->coinService = $coinService;
        $this->transactionService = $transactionService;
    }

    public function execute(UpdateTransactionByIdRequest $request): UpdateTransactionByIdResponse
    {
        $transaction = $this->transactionService->getById($request->transactionId);

        if ($request->portfolioId) {
            $portfolio = $this->portfolioService->getByIdAndUserId($request->portfolioId, $request->userId);
            $transaction->portfolio_id = $portfolio->id;
        }

        if ($request->coinId) {
            $coin = $this->coinService->getById($request->coinId);
            $transaction->coin_id = $coin->id;
        }

        if ($request->type) {
            $transaction->type = $request->type->value;
        }

        if ($request->pricePerCoin) {
            $transaction->price_per_coin = $request->pricePerCoin;
        }

        if ($request->quantity) {
            $transaction->quantity = $request->quantity;
        }

        if ($request->fee) {
            $transaction->fee = $request->fee;
        }

        if ($request->datetime) {
            $transaction->datetime = $request->datetime;
        }

        $transaction->save();

        $coinMarketData = $this->client->coinMarketData($coin->name, $coin->symbol);

        $cost = $this->calculator->cost($request->quantity, $request->pricePerCoin, $request->fee);
        $currentValue = $this->calculator->value($request->quantity, $coinMarketData->price);
        $valueChange = $this->calculator->valueChange($currentValue, $cost);

        $transactionEntity = TransactionEntity::create($transaction, $cost, $currentValue, $valueChange);

        return new UpdateTransactionByIdResponse([
            'transaction' => $transactionEntity,
        ]);
    }
}
