<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use Spatie\DataTransferObject\DataTransferObject;

final class GetCoinsRequest extends DataTransferObject
{
    public int $page;
    public int $perPage;
}
