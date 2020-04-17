<?php

declare(strict_types=1);

namespace App\Coinfo\Factories\Coinpaprika;

use App\Coinfo\Types\CoinHistoricalData;
use App\Coinfo\Types\CoinHistoricalDataCollection;
use Carbon\Carbon;

final class CoinHistoricalDataCollectionFactory
{
    public static function create(array $data): CoinHistoricalDataCollection
    {
        $arrayOfHistorical = array_map(
            fn ($historical) => new CoinHistoricalData([
                'timestamp' => Carbon::parse($historical['timestamp']),
                'price' => $historical['price'],
                'volume' => $historical['volume_24h'],
                'marketCap' => $historical['market_cap'],
            ]),
            $data
        );
        return new CoinHistoricalDataCollection($arrayOfHistorical);
    }
}
