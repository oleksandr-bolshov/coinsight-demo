<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Transactions;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

final class GetTransactionsResponse extends DataTransferObject
{
    public Collection $transactions;
}
