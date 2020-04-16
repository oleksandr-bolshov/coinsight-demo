<?php

declare(strict_types=1);

namespace App\Coinfo\Factories\CoinGecko;

use App\Coinfo\Types\CoinOverview;
use App\Coinfo\Types\CoinOverviewCollection;

final class CoinOverviewCollectionFactory
{
    public static function create(array $data): CoinOverviewCollection
    {
        $arrayOfCoins = array_map(
            function($coin) {
                return new CoinOverview([
                    'name' => $coin['name'],
                    'symbol' => $coin['symbol'],
                    'icon' => $coin['image'],
                    'rank' => $coin['market_cap_rank'],
                    'price' => $coin['current_price'],
                    'priceChange24h' => $coin['price_change_percentage_24h'] ?? null,
                    'marketCap' => $coin['market_cap'],
                    'volume' => $coin['total_volume']
                ]);
            },
            $data
        );
        return new CoinOverviewCollection($arrayOfCoins);
    }
}
