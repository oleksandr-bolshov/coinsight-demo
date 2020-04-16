<?php

declare(strict_types=1);

namespace App\Coinfo\Aggregators;

use App\Coinfo\Factories\CoinGecko\CoinOverviewCollectionFactory;
use App\Coinfo\Factories\CoinGecko\CoinPriceByTimeCollectionFactory;
use App\Coinfo\Types\CoinOverviewCollection;
use App\Coinfo\Types\CoinPriceByTimeCollection;

final class CoinGecko extends Aggregator
{
    public const BASE_URL = 'https://api.coingecko.com/api/v3/';

    public function coinsMarkets(
        int $page = 1,
        int $perPage = 100,
        array $ids = [],
        bool $sparkline = false
    ): CoinOverviewCollection {
        $data = $this->request('coins/markets', [
            'vs_currency' => 'usd',
            'page' => $page,
            'per_page' => $perPage,
            'ids' => implode(",", $ids),
            'sparkline' => $sparkline,
        ]);

        return CoinOverviewCollectionFactory::create($data);
    }

    public function coinMarketChart(string $id, int $days): CoinPriceByTimeCollection
    {
        $data = $this->request("/coins/{$id}/market_chart", [
            'vs_currency' => 'usd',
            'days' => $days,
        ]);

        return CoinPriceByTimeCollectionFactory::create($data);
    }
}
