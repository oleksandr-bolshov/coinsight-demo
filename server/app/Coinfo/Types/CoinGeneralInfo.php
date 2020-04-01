<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

final class CoinGeneralInfo extends DataTransferObject
{
    public string $name;
    public string $symbol;
    public int $rank;
    public bool $isNew;
    public bool $isActive;
    public string $type;
    public string $description;
    public bool $isOpenSource;
    public Carbon $startedAt;
    public string $developmentStatus;
    public bool $hasHardwareWallet;
    public string $proofType;
    public string $orgStructure;
    public string $hashAlgorithm;
    public LinkCollection $links;
    public Whitepaper $whitepaper;
}
