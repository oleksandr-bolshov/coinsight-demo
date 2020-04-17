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
            'volumeChange24h' => $usdData['volume_24h_change_24h'],
            'marketCap' => $usdData['market_cap'],
            'marketCapChange24h' => $usdData['market_cap_change_24h'],
            'priceChange1h' => $usdData['percent_change_1h'],
            'priceChange24h' => $usdData['percent_change_24h'],
            'priceChange7d' => $usdData['percent_change_7d'],
            'priceChange30d' => $usdData['percent_change_30d'],
            'priceChange1y' => $usdData['percent_change_1y'],
        ]);
    }
}
