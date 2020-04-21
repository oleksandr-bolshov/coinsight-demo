<?php

declare(strict_types=1);

namespace App\Http\Resources\Portfolios;

use App\Domain\Portfolios\Entities\Portfolio;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class PortfolioCollectionResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        $portfolios = $this->map(fn(Portfolio $portfolio) => new PortfolioResource($portfolio));

        return [
            'portfolios' => $portfolios,
        ];
    }
}
