<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Transactions;

use App\Domain\Portfolios\Services\TransactionService;

final class DeleteTransactionByIdInteractor
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function execute(DeleteTransactionByIdRequest $request): DeleteTransactionByIdResponse
    {
        $transaction = $this->transactionService->getById($request->transactionId);

        $transaction->delete();

        return new DeleteTransactionByIdResponse([
            'id' => $transaction->id,
        ]);
    }
}
