<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Transactions;

use App\Coinfo\Client;
use App\Domain\Markets\Services\CoinService;
use App\Domain\Portfolios\Entities\Transaction as TransactionEntity;
use App\Domain\Portfolios\Models\Transaction;
use App\Domain\Portfolios\Services\PortfolioService;
use App\Domain\Portfolios\Services\TransactionCalculator;
use App\Domain\Portfolios\Services\TransactionService;

final class CreateTransactionInteractor
{
    private Client $client;
    private TransactionCalculator $transactionCalculator;
    private PortfolioService $portfolioService;
    private CoinService $coinService;
    private TransactionService $transactionService;

    public function __construct(
        Client $client,
        TransactionCalculator $transactionCalculator,
        PortfolioService $portfolioService,
        CoinService $coinService,
        TransactionService $transactionService
    ) {
        $this->client = $client;
        $this->transactionCalculator = $transactionCalculator;
        $this->portfolioService = $portfolioService;
        $this->coinService = $coinService;
        $this->transactionService = $transactionService;
    }

    public function execute(CreateTransactionRequest $request): CreateTransactionResponse
    {
        $portfolio = $this->portfolioService->getByIdAndUserId($request->portfolioId, $request->userId);

        $coin = $this->coinService->getById($request->coinId);

        $transaction = new Transaction();
        $transaction->type = $request->type->value;
        $transaction->price_per_coin = $request->pricePerCoin;
        $transaction->quantity = $request->quantity;
        $transaction->fee = $request->fee;
        $transaction->datetime = $request->datetime ?? now();
        $transaction->portfolio_id = $portfolio->id;
        $transaction->coin_id = $coin->id;

        $transaction = $this->transactionService->store($transaction);

        $coinMarketData = $this->client->coinMarketData($coin->name, $coin->symbol);

        $cost = $this->transactionCalculator->cost($request->quantity, $request->pricePerCoin, $request->fee);
        $currentValue = $this->transactionCalculator->value($request->quantity, $coinMarketData->price);
        $valueChange = $this->transactionCalculator->valueChange($currentValue, $cost);

        $transactionEntity = TransactionEntity::create($transaction, $cost, $currentValue, $valueChange);

        return new CreateTransactionResponse([
            'transaction' => $transactionEntity,
        ]);
    }
}
