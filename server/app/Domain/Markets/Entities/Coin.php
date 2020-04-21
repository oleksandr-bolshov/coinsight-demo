<?php

declare(strict_types=1);

namespace App\Domain\Markets\Entities;

use App\Domain\Markets\Models\Coin as CoinModel;
use Spatie\DataTransferObject\DataTransferObject;

final class Coin extends DataTransferObject
{
    public int $id;
    public string $name;
    public string $symbol;
    public ?string $icon;

    public static function fromModel(CoinModel $coin): self
    {
        return new static([
            'id' => $coin->id,
            'name' => $coin->name,
            'symbol' => $coin->symbol,
            'icon' => $coin->icon,
        ]);
    }
}
