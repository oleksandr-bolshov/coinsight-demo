<?php

declare(strict_types=1);

namespace App\Http\Markets\Resources;

use App\Domain\Markets\Entities\CoinHistoricalData;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class CoinHistoricalDataCollectionResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        $historical = $this->map(
            fn (CoinHistoricalData $historical) => [
                'timestamp' => $historical->timestamp->getPreciseTimestamp(3),
                'price' => $historical->price,
                'market_cap' => $historical->marketCap,
                'volume' => $historical->volume,
            ]
        );

        return [
            'historical_data' => $historical,
        ];
    }
}
