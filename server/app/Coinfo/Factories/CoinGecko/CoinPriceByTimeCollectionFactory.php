<?php

declare(strict_types=1);

namespace App\Coinfo\Factories\CoinGecko;

use App\Coinfo\Types\CoinPriceByTime;
use App\Coinfo\Types\CoinPriceByTimeCollection;
use Carbon\Carbon;

final class CoinPriceByTimeCollectionFactory
{
    public static function create(array $data): CoinPriceByTimeCollection
    {
        $collection = new CoinPriceByTimeCollection();

        for ($i = 0; $i < count($data['prices']); $i++) {
            $collection[] = new CoinPriceByTime([
                'timestamp' => Carbon::parse($data['prices'][$i][0]),
                'price' => $data['prices'][$i][1],
                'volume' => $data['market_caps'][$i][1],
                'marketCap' => $data['total_volumes'][$i][1],
            ]);
        }

        return $collection;
    }
}
