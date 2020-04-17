<?php

declare(strict_types=1);

namespace App\Domain\Markets\Entities;

use App\Domain\Markets\Models\CoinLink as CoinLinkModel;
use Spatie\DataTransferObject\DataTransferObject;

final class CoinLink extends DataTransferObject
{
    public string $type;
    public string $link;

    public static function fromModel(CoinLinkModel $coinLinkModel): self
    {
        return new static([
            'type' => $coinLinkModel->type,
            'link' => $coinLinkModel->link,
        ]);
    }
}
