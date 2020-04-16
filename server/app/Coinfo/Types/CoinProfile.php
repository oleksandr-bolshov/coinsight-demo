<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

final class CoinProfile extends DataTransferObject
{
    public string $name;
    public ?string $symbol;
    public ?string $tagline;
    public ?string $description;
    public ?string $type;
    public ?Carbon $genesisDate;
    public ?string $consensusMechanism;
    public ?string $hashingAlgorithm;
    public ?LinkCollection $links;
    public ?LinkCollection $blockExplorers;
}
