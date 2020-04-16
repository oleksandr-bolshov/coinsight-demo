<?php

declare(strict_types=1);

namespace App\Coinfo\Aggregators;

use App\Coinfo\Enums\Interval;
use App\Coinfo\Factories\Coinpaprika\CoinMarketDataFactory;
use App\Coinfo\Factories\Coinpaprika\GlobalStatsFactory;
use App\Coinfo\Factories\Coinpaprika\CoinPriceByTimeCollectionFactory;
use App\Coinfo\Types\CoinMarketData;
use App\Coinfo\Types\GlobalStats;
use App\Coinfo\Types\CoinPriceByTimeCollection;
use Carbon\Carbon;

final class Coinpaprika extends Aggregator
{
    public const BASE_URL = 'https://api.coinpaprika.com/v1/';

    public function globalStats(): GlobalStats
    {
        $data = $this->request("global");
        return GlobalStatsFactory::create($data);
    }

    public function tickerByCoinId(string $id): CoinMarketData
    {
        $data = $this->request("tickers/{$id}");
        return CoinMarketDataFactory::create($data);
    }

    public function tickerHistoricalByCoinId(
        string $id,
        ?Carbon $start = null,
        ?Carbon $end = null,
        int $limit = 1000,
        ?Interval $interval = null
    ): CoinPriceByTimeCollection {
        $end ??= $now = Carbon::now();
        $start ??= $now->subDay();
        $interval ??= Interval::FIVE_MINUTES;

        $data = $this->request("/tickers/{$id}/historical", [
            'start' => $start,
            'end' => $end,
            'limit' => $limit,
            'interval' => $interval
        ]);

        return CoinPriceByTimeCollectionFactory::create($data);
    }
}
