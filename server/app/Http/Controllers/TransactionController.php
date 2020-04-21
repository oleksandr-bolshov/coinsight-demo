<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Portfolios\Enums\TransactionType;
use App\Domain\Portfolios\Interactors\Transactions\CreateTransactionInteractor;
use App\Domain\Portfolios\Interactors\Transactions\CreateTransactionRequest;
use App\Http\ApiResponse;
use App\Http\Requests\Transactions\CreateTransactionApiRequest;
use App\Http\Resources\Transactions\TransactionResource;

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
}
