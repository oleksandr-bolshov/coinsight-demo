<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

final class GetCoinsResponse extends DataTransferObject
{
    public Collection $coins;
    public int $total;
    public int $page;
    public int $perPage;
}
