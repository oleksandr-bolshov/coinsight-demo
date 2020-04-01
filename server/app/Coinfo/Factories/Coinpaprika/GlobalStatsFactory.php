<?php

declare(strict_types=1);

namespace App\Coinfo\Factories\Coinpaprika;

use App\Coinfo\Types\GlobalStats;

final class GlobalStatsFactory
{
    public static function create(array $data): GlobalStats
    {
        return new GlobalStats([
            'marketCap' => $data['market_cap_usd'],
            'volume' => $data['volume_24h_usd'],
            'bitcoinDominance' => $data['bitcoin_dominance_percentage'],
            'marketCapChange' => $data['market_cap_change_24h'],
            'volumeChange' => $data['volume_24h_change_24h'],
        ]);
    }
}
