<?php

declare(strict_types=1);

namespace App\Coinfo\Aggregators;

use App\Coinfo\Enums\Interval;
use App\Coinfo\Factories\Coinpaprika\CoinGeneralInfoFactory;
use App\Coinfo\Factories\Coinpaprika\CoinMarketDataFactory;
use App\Coinfo\Factories\Coinpaprika\GlobalStatsFactory;
use App\Coinfo\Factories\Coinpaprika\HistoricalCollectionFactory;
use App\Coinfo\Types\CoinGeneralInfo;
use App\Coinfo\Types\CoinMarketData;
use App\Coinfo\Types\GlobalStats;
use App\Coinfo\Types\HistoricalCollection;
use Carbon\Carbon;

final class Coinpaprika extends Aggregator
{
    public const BASE_URL = 'https://api.coinpaprika.com/v1/';

    public function globalStats(): GlobalStats
    {
        $data = $this->request("global");
        return GlobalStatsFactory::create($data);
    }

    public function coinByCoinId(string $id): CoinGeneralInfo
    {
        $data = $this->request("coins/{$id}");
        return CoinGeneralInfoFactory::create($data);
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
    ): HistoricalCollection {
        $end ??= $now = Carbon::now();
        $start ??= $now->subDay();
        $interval ??= Interval::FIVE_MINUTES;

        $data = $this->request("/tickers/{$id}/historical", [
            'start' => $start,
            'end' => $end,
            'limit' => $limit,
            'interval' => $interval
        ]);

        return HistoricalCollectionFactory::create($data);
    }
}
