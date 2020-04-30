<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Transactions;

use Spatie\DataTransferObject\DataTransferObject;

final class DeleteTransactionByIdResponse extends DataTransferObject
{
    public int $id;
}
