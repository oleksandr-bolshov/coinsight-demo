<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Transactions;

use App\Domain\Portfolios\Enums\TransactionType;
use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

final class UpdateTransactionByIdRequest extends DataTransferObject
{
    public int $transactionId;
    public int $userId;
    public ?int $portfolioId;
    public ?int $coinId;
    public ?TransactionType $type;
    public ?float $pricePerCoin;
    public ?float $quantity;
    public ?float $fee;
    public ?Carbon $datetime;
}
