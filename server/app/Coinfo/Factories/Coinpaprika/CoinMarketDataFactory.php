<?php

declare(strict_types=1);

namespace App\Coinfo\Factories\Coinpaprika;

use App\Coinfo\Types\CoinMarketData;

final class CoinMarketDataFactory
{
    static function create(array $data): CoinMarketData
    {
        $usdData = $data['quotes']['USD'];
        return new CoinMarketData([
            'name' => $data['name'],
            'symbol' => $data['symbol'],
            'rank' => $data['rank'],
            'circulatingSupply' => $data['circulating_supply'],
            'maxSupply' => $data['max_supply'],
            'price' => $usdData['price'],
            'volume' => $usdData['volume_24h'],
            'volumeChange' => $usdData['volume_24h_change_24h'],
            'marketCap' => $usdData['market_cap'],
            'marketCapChange' => $usdData['market_cap_change_24h'],
            'change1h' => $usdData['percent_change_1h'],
            'change12h' => $usdData['percent_change_12h'],
            'change24h' => $usdData['percent_change_24h'],
            'change7d' => $usdData['percent_change_7d'],
            'change30d' => $usdData['percent_change_30d'],
            'change1y' => $usdData['percent_change_1y'],
        ]);
    }
}
