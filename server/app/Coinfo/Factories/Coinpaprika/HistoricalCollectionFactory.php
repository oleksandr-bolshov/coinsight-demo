<?php

declare(strict_types=1);

namespace App\Coinfo\Factories\Coinpaprika;

use App\Coinfo\Types\Historical;
use App\Coinfo\Types\HistoricalCollection;
use Carbon\Carbon;

final class HistoricalCollectionFactory
{
    public static function create(array $data): HistoricalCollection
    {
        $arrayOfHistorical = array_map(
            fn ($historical) => new Historical([
                'timestamp' => Carbon::parse($historical['timestamp']),
                'price' => $historical['price'],
                'volume' => $historical['volume_24h'],
                'marketCap' => $historical['market_cap'],
            ]),
            $data
        );
        return new HistoricalCollection($arrayOfHistorical);
    }
}
