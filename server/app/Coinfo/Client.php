<?php

declare(strict_types=1);

namespace App\Coinfo;

use App\Coinfo\Aggregators\CoinGecko;
use App\Coinfo\Aggregators\Coinpaprika;
use App\Coinfo\Aggregators\CoinStats;
use App\Coinfo\Aggregators\Messari;
use App\Coinfo\Enums\Interval;
use App\Coinfo\Types\CoinHistoricalData;
use App\Coinfo\Types\CoinOverview;
use App\Coinfo\Types\CoinOverviewCollection;
use App\Coinfo\Types\CoinProfile;
use App\Coinfo\Types\CoinMarketData;
use App\Coinfo\Types\CoinOHLCVCollection;
use App\Coinfo\Types\GlobalStats;
use App\Coinfo\Types\CoinHistoricalDataCollection;
use App\Domain\Markets\Models\Coin;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
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
        //return $this->coinpaprika->globalStats();
        return new GlobalStats([
            'marketCap' => 157_243_568_231,
            'volume' => 351_834_192_725,
            'bitcoinDominance' => 62.5,
            'marketCapChange' => -1.75,
            'volumeChange' => 5.34,
        ]);
    }

    public function markets(int $page = 1, int $perPage = 100): CoinOverviewCollection
    {
        return $this->coinGecko->coinsMarkets($page, $perPage);
    }

    public function marketsForCoins(array $currenciesNames): CoinOverviewCollection
    {
        /*$slugged = array_map(
            fn(string $currencyName) => Str::slug($currencyName),
            $currenciesNames
        );
        return $this->coinGecko->coinsMarkets(0, 0, $slugged);*/
        $coins = Coin::whereIn('name', ['Bitcoin', 'Ethereum'])->get();

        $coinOverviewCollection = new CoinOverviewCollection();
        foreach ($coins as $coin) {
            $coinOverviewCollection[] = new CoinOverview([
                'name' => $coin->name,
                'symbol' => $coin->symbol,
                'icon' => $coin->icon,
                'rank' => $coin->id,
                'price' => $coin->name === 'Bitcoin' ? 7685.85 : 181.35,
                'priceChange24h' => 2.47,
                'marketCap' => 1,
                'volume' => 1,
            ]);
        }
        return $coinOverviewCollection;
    }

    public function coinProfile(string $currencyName): CoinProfile
    {
        return $this->messari->assetProfile(Str::slug($currencyName));
    }

    public function coinMarketData(string $currencyName, string $currencySymbol): CoinMarketData
    {
        /*return $this->coinpaprika->tickerByCoinId(
            $this->getCoinpaprikaCoinId($currencyName, $currencySymbol)
        );*/
        return new CoinMarketData([
            'name' => $currencyName,
            'symbol' => $currencySymbol,
            'rank' => random_int(1, 100),
            'circulatingSupply' => 18_123_456,
            'maxSupply' => 21_000_000,
            'price' => 7568,
            'volume' => 210_312_543,
            'volumeChange24h' => 2.35,
            'marketCap' => 190_312_543,
            'marketCapChange24h' => -4.37,
            'priceChange1h' => -1.76,
            'priceChange24h' => 3.61,
            'priceChange7d' => 1.73,
            'priceChange30d' => 5.91,
            'priceChange1y' => -2.72,
        ]);
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
        //return $this->coinGecko->coinMarketChart(Str::slug($currencyName), $days);
        $historicalCollection = new CoinHistoricalDataCollection();

        if ($days === 1) {
            $count = 1440;
            $ago = Carbon::now()->subDay();

            for ($i = 0; $i < $count; $i++) {
                $historicalCollection[] = new CoinHistoricalData([
                    'timestamp' => $ago->clone()->addMinutes($i),
                    'price' => random_int(6000, 8000),
                    'marketCap' => random_int(100_000_000, 300_000_000),
                    'volume' => random_int(100_000_000, 300_000_000),
                ]);
            }
        } else if ($days > 1 && $days < 90) {
            $count = 24 * $days;
            $ago = Carbon::now()->subDays($days);

            for ($i = 0; $i < $count; $i++) {
                $historicalCollection[] = new CoinHistoricalData([
                    'timestamp' => $ago->clone()->addHours($i),
                    'price' => random_int(6000, 8000),
                    'marketCap' => random_int(100_000_000, 300_000_000),
                    'volume' => random_int(100_000_000, 300_000_000),
                ]);
            }
        } else {
            $count = $days;
            $ago = Carbon::now()->subDays($days);

            for ($i = 0; $i < $count; $i++) {
                $historicalCollection[] = new CoinHistoricalData([
                    'timestamp' => $ago->clone()->addDays($i),
                    'price' => random_int(6000, 8000),
                    'marketCap' => random_int(100_000_000, 300_000_000),
                    'volume' => random_int(100_000_000, 300_000_000),
                ]);
            }
        }

        return $historicalCollection;
    }

    public function coinHistoricalDataAllTime(string $currencyName): CoinHistoricalDataCollection
    {
        //return $this->coinGecko->coinMarketChart(Str::slug($currencyName), 'max');
        return $this->coinHistoricalData($currencyName, 180);
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
