<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Transactions;

use Spatie\DataTransferObject\DataTransferObject;

final class DeleteTransactionByIdRequest extends DataTransferObject
{
    public int $userId;
    public int $portfolioId;
    public int $transactionId;
}
