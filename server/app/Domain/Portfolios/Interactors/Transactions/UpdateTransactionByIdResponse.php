<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Transactions;

use App\Domain\Portfolios\Entities\Transaction;
use Spatie\DataTransferObject\DataTransferObject;

final class UpdateTransactionByIdResponse extends DataTransferObject
{
    public Transaction $transaction;
}
