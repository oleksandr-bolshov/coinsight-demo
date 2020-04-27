<?php

declare(strict_types=1);

namespace App\Coinfo;

use App\Coinfo\Aggregators\CoinGecko;
use App\Coinfo\Aggregators\Coinpaprika;
use App\Coinfo\Aggregators\CoinStats;
use App\Coinfo\Aggregators\Messari;
use App\Coinfo\Enums\Interval;
use App\Coinfo\Types\CoinOverviewCollection;
use App\Coinfo\Types\CoinProfile;
use App\Coinfo\Types\CoinMarketData;
use App\Coinfo\Types\CoinOHLCVCollection;
use App\Coinfo\Types\GlobalStats;
use App\Coinfo\Types\CoinHistoricalDataCollection;
use Carbon\Carbon;
use Illuminate\Support\Str;

final class Client
{
    private Coinpaprika $coinpaprika;
    private CoinGecko $coinGecko;
    private CoinStats $coinStats;
    private Messari $messari;

    public function __construct(
        Coinpaprika $coinpaprika,
        CoinGecko $coinGecko,
        CoinStats $coinStats,
        Messari $messari
    ) {
        $this->coinpaprika = $coinpaprika;
        $this->coinGecko = $coinGecko;
        $this->coinStats = $coinStats;
        $this->messari = $messari;
    }

    public function globalStats(): GlobalStats
    {
        return $this->coinpaprika->globalStats();
    }

    public function markets(int $page = 1, int $perPage = 100): CoinOverviewCollection
    {
        return $this->coinGecko->coinsMarkets($page, $perPage);
    }

    public function marketsForCoins(array $currenciesNames): CoinOverviewCollection
    {
        $slugged = array_map(
            fn(string $currencyName) => Str::slug($currencyName),
            $currenciesNames
        );
        return $this->coinGecko->coinsMarkets(0, 0, $slugged);
    }

    public function coinProfile(string $currencyName): CoinProfile
    {
        return $this->messari->assetProfile(Str::slug($currencyName));
    }

    public function coinMarketData(string $currencyName, string $currencySymbol): CoinMarketData
    {
        return $this->coinpaprika->tickerByCoinId(
            $this->getCoinpaprikaCoinId($currencyName, $currencySymbol)
        );
    }

    public function coinHistoricalDataByDateRange(
        string $currencyName,
        string $currencySymbol,
        ?Carbon $start = null,
        ?Carbon $end = null,
        int $limit = 1000,
        ?Interval $interval = null
    ): CoinHistoricalDataCollection {
        return $this->coinpaprika->tickerHistoricalByCoinId(
            $this->getCoinpaprikaCoinId($currencyName, $currencySymbol),
            $start,
            $end,
            $limit,
            $interval
        );
    }

    public function coinHistoricalData(string $currencyName, int $days): CoinHistoricalDataCollection {
        return $this->coinGecko->coinMarketChart(Str::slug($currencyName), $days);
    }

    public function coinHistoricalDataAllTime(string $currencyName): CoinHistoricalDataCollection
    {
        return $this->coinGecko->coinMarketChart(Str::slug($currencyName), 'max');
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
