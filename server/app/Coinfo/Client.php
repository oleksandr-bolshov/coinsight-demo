<?php

declare(strict_types=1);

namespace App\Coinfo;

use App\Coinfo\Aggregators\Coinpaprika;
use App\Coinfo\Aggregators\CoinStats;
use App\Coinfo\Aggregators\Messari;
use App\Coinfo\Enums\Interval;
use App\Coinfo\Types\CoinOverviewCollection;
use App\Coinfo\Types\CoinGeneralInfo;
use App\Coinfo\Types\CoinMarketData;
use App\Coinfo\Types\CoinOHLCVCollection;
use App\Coinfo\Types\GlobalStats;
use App\Coinfo\Types\HistoricalCollection;
use Carbon\Carbon;
use Illuminate\Support\Str;

final class Client
{
    private Coinpaprika $coinpaprika;
    private CoinStats $coinStats;
    private Messari $messari;

    public function __construct(
        Coinpaprika $coinpaprika,
        CoinStats $coinStats,
        Messari $messari
    ) {
        $this->coinpaprika = $coinpaprika;
        $this->coinStats = $coinStats;
        $this->messari = $messari;
    }

    public function globalStats(): GlobalStats
    {
        return $this->coinpaprika->globalStats();
    }

    public function markets(int $skip = 0, int $limit = 5): CoinOverviewCollection
    {
        return $this->coinStats->coins($skip, $limit);
    }

    public function coinGeneralInfo(string $currencyName, string $currencySymbol): CoinGeneralInfo
    {
        return $this->coinpaprika->coinByCoinId(
            $this->getCoinpaprikaCoinId($currencyName, $currencySymbol)
        );
    }

    public function coinMarketData(string $currencyName, string $currencySymbol): CoinMarketData
    {
        return $this->coinpaprika->tickerByCoinId(
            $this->getCoinpaprikaCoinId($currencyName, $currencySymbol)
        );
    }

    public function coinPriceByTime(
        string $currencyName,
        string $currencySymbol,
        ?Carbon $start = null,
        ?Carbon $end = null,
        int $limit = 1000,
        ?Interval $interval = null
    ): HistoricalCollection {
        return $this->coinpaprika->tickerHistoricalByCoinId(
            $this->getCoinpaprikaCoinId($currencyName, $currencySymbol),
            $start,
            $end,
            $limit,
            $interval
        );
    }

    public function coinOHLCV(
        string $currencyName,
        ?Carbon $start = null,
        ?Carbon $end = null,
        ?Interval $interval = null
    ): CoinOHLCVCollection {
        return $this->messari->assetTimeseries(
            Str::slug($currencyName),
            $start,
            $end,
            $interval
        );
    }

    private function getCoinpaprikaCoinId(string $currencyName, string $currencySymbol): string
    {
        return Str::slug($currencySymbol . " " . $currencyName);
    }
}
