<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Transactions;

use App\Coinfo\Client;
use App\Domain\Common\Exceptions\ModelNotFound;
use App\Domain\Markets\Exceptions\CoinNotFound;
use App\Domain\Markets\Models\Coin;
use App\Domain\Portfolios\Entities\Transaction as TransactionEntity;
use App\Domain\Portfolios\Exceptions\PortfolioNotFound;
use App\Domain\Portfolios\Models\Portfolio;
use App\Domain\Portfolios\Models\Transaction;
use App\Domain\Portfolios\Services\TransactionCalculator;

final class CreateTransactionInteractor
{
    private Client $client;
    private TransactionCalculator $transactionCalculator;

    public function __construct(Client $client, TransactionCalculator $transactionCalculator)
    {
        $this->client = $client;
        $this->transactionCalculator = $transactionCalculator;
    }

    public function execute(CreateTransactionRequest $request): CreateTransactionResponse
    {
        try {
            $portfolio = Portfolio::whereId($request->portfolioId)
                ->whereUserId($request->userId)
                ->firstOrFail();
        } catch (ModelNotFound $exception) {
            throw new PortfolioNotFound();
        }

        try {
            $coin = Coin::findOrFail($request->coinId);
        } catch (ModelNotFound $exception) {
            throw new CoinNotFound();
        }

        $transaction = new Transaction();
        $transaction->type = $request->type->value;
        $transaction->price_per_coin = $request->pricePerCoin;
        $transaction->quantity = $request->quantity;
        $transaction->fee = $request->fee;
        $transaction->datetime = $request->datetime ?? now();
        $transaction->portfolio_id = $portfolio->id;
        $transaction->coin_id = $coin->id;
        $transaction->save();
        $transaction->refresh();

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
