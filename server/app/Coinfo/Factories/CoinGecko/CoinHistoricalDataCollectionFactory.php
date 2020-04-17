<?php

declare(strict_types=1);

namespace App\Coinfo\Factories\CoinGecko;

use App\Coinfo\Types\CoinHistoricalData;
use App\Coinfo\Types\CoinHistoricalDataCollection;
use Carbon\Carbon;

final class CoinHistoricalDataCollectionFactory
{
    public static function create(array $data): CoinHistoricalDataCollection
    {
        $collection = new CoinHistoricalDataCollection();
        for ($i = 0; $i < count($data['prices']); $i++) {
            $collection[] = new CoinHistoricalData([
                'timestamp' => Carbon::createFromTimestampMs($data['prices'][$i][0]),
                'price' => $data['prices'][$i][1],
                'volume' => $data['market_caps'][$i][1],
                'marketCap' => $data['total_volumes'][$i][1],
            ]);
        }

        return $collection;
    }
}
