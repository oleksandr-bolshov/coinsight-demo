<?php

declare(strict_types=1);

namespace App\Domain\Markets\Entities;

use Spatie\DataTransferObject\DataTransferObject;

final class Coin extends DataTransferObject
{
    public int $id;
    public string $name;
    public string $symbol;
    public ?string $icon;
    public int $rank;
    public float $price;
    public ?float $priceChange24h;
    public float $marketCap;
    public float $volume;
}
