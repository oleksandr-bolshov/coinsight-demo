<?php

declare(strict_types=1);

namespace App\Coinfo\Factories\CoinStats;

use App\Coinfo\Types\CoinOverview;
use App\Coinfo\Types\CoinOverviewCollection;
use Exception;

final class CoinCollectionFactory
{
    public static function create(array $data): CoinOverviewCollection
    {
        $arrayOfCoins = array_map(
            function($coin) {
                return new CoinOverview([
                    'name' => $coin['name'],
                    'symbol' => $coin['symbol'],
                    'icon' => $coin['icon'],
                    'rank' => $coin['rank'],
                    'price' => $coin['price'],
                    'change24h' => $coin['priceChange1d'] ?? null,
                    'marketCap' => $coin['marketCap'],
                    'volume' => $coin['volume']
                ]);
            },
            $data['coins']
        );
        return new CoinOverviewCollection($arrayOfCoins);
    }
}
