<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Entities;

use App\Domain\Portfolios\Models\Portfolio as PortfolioModel;
use Spatie\DataTransferObject\DataTransferObject;

final class Portfolio extends DataTransferObject
{
    public int $id;
    public string $name;

    public static function fromModel(PortfolioModel $portfolio): self
    {
        return new static([
            'id' => $portfolio->id,
            'name' => $portfolio->name,
        ]);
    }
}
