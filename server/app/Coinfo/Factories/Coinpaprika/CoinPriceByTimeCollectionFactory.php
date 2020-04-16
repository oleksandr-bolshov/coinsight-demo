<?php

declare(strict_types=1);

namespace App\Coinfo\Factories\Coinpaprika;

use App\Coinfo\Types\CoinPriceByTime;
use App\Coinfo\Types\CoinPriceByTimeCollection;
use Carbon\Carbon;

final class CoinPriceByTimeCollectionFactory
{
    public static function create(array $data): CoinPriceByTimeCollection
    {
        $arrayOfHistorical = array_map(
            fn ($historical) => new CoinPriceByTime([
                'timestamp' => Carbon::parse($historical['timestamp']),
                'price' => $historical['price'],
                'volume' => $historical['volume_24h'],
                'marketCap' => $historical['market_cap'],
            ]),
            $data
        );
        return new CoinPriceByTimeCollection($arrayOfHistorical);
    }
}
