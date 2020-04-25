<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Controllers;

use App\Domain\Portfolios\Enums\TransactionType;
use App\Domain\Portfolios\Interactors\Transactions\CreateTransactionInteractor;
use App\Domain\Portfolios\Interactors\Transactions\CreateTransactionRequest;
use App\Domain\Portfolios\Interactors\Transactions\GetTransactionsInteractor;
use App\Domain\Portfolios\Interactors\Transactions\GetTransactionsRequest;
use App\Http\Portfolios\Requests\CreateTransactionApiRequest;
use App\Http\Portfolios\Requests\GetTransactionApiRequest;
use App\Http\Portfolios\Resources\TransactionCollectionResource;
use App\Http\Portfolios\Resources\TransactionResource;
use App\Http\Common\ApiResponse;

final class TransactionController
{
    public function createTransaction(
        CreateTransactionApiRequest $request,
        CreateTransactionInteractor $createTransactionInteractor
    ): ApiResponse {
        $transaction = $createTransactionInteractor
            ->execute(new CreateTransactionRequest([
                'userId' => $request->userId(),
                'portfolioId' => $request->portfolioId(),
                'coinId' => $request->coinId(),
                'type' => new TransactionType($request->type()),
                'pricePerCoin' => $request->pricePerCoin(),
                'quantity' => $request->quantity(),
                'fee' => $request->fee(),
                'datetime' => $request->datetime(),
            ]))
            ->transaction;

        return ApiResponse::success(new TransactionResource($transaction));
    }

    public function getTransactions(
        GetTransactionApiRequest $request,
        GetTransactionsInteractor $transactionsInteractor
    ): ApiResponse {
        $transactions = $transactionsInteractor
            ->execute(new GetTransactionsRequest([
                'userId' => $request->userId(),
                'portfolioId' => $request->portfolioId(),
            ]))
            ->transactions;

        return ApiResponse::success(new TransactionCollectionResource($transactions));
    }
}
