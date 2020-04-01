<?php

declare(strict_types=1);

namespace Tests\Feature\Coinfo;

use App\Coinfo\Aggregators\Coinpaprika;
use App\Coinfo\Aggregators\CoinStats;
use App\Coinfo\Aggregators\Messari;
use Illuminate\Support\Facades\Http;

trait CoinfoDataProvider
{
    public function fakeCoinfo(): void
    {
        $requestsMap = [];
        foreach ($this->methodMapper() as $coinfoMethod) {
            $requestsMap[$coinfoMethod['url']] = Http::response($coinfoMethod['response']);
        }
        Http::fake($requestsMap);
    }

    private function methodMapper(): array
    {
        return [
            'globalStats' => [
                'url' => $this->getEndpointUrl(
                    Coinpaprika::BASE_URL,
                    'global'
                ),
                'response' => $this->fakeGlobalStatsResponse(),
            ],
            'markets' => [
                'url' => $this->getEndpointUrlWithWildcard(
                    CoinStats::BASE_URL,
                    'coins'
                ),
                'response' => $this->fakeMarketsResponse(),
            ],
            'coinGeneralInfo' => [
                'url' => $this->getEndpointUrl(
                    Coinpaprika::BASE_URL,
                    'coins/symbol-currency-name'
                ),
                'response' => $this->fakeCoinGeneralInfoResponse(),
            ],
            'coinMarketData' => [
                'url' => $this->getEndpointUrl(
                    Coinpaprika::BASE_URL,
                    'tickers/symbol-currency-name'
                ),
                'response' => $this->fakeCoinMarketDataResponse(),
            ],
            'coinPriceByTime' => [
                'url' => $this->getEndpointUrlWithWildcard(
                    Coinpaprika::BASE_URL,
                    'tickers/symbol-currency-name/historical'
                ),
                'response' => $this->fakeCoinPriceByTimeResponse(),
            ],
            'coinOHLCV' => [
                'url' => $this->getEndpointUrlWithWildcard(
                    Messari::BASE_URL,
                    'assets/currency-name/metrics/price/time-series'
                ),
                'response' => $this->fakeCoinOHLCVResponse(),
            ],
            '*' => [
                'url' => '*',
                'response' => [],
            ]
        ];
    }

    public function fakeGlobalStatsResponse(): array
    {
        return [
            'market_cap_usd' => 123,
            'volume_24h_usd' => 123,
            'bitcoin_dominance_percentage' => 12.345,
            'market_cap_change_24h' => -1.23,
            'volume_24h_change_24h' => -1.23,
        ];
    }

    public function fakeMarketsResponse(): array
    {
        return [
            'coins' => [
                [
                    'icon' => 'icon1',
                    'name' => 'name1',
                    'symbol' => 'symbol1',
                    'rank' => 1,
                    'price' => 1234.567,
                    'volume' => 1234.567,
                    'marketCap' => 1234.567,
                    'priceChange1d' => -12.34,
                ],
                [
                    'icon' => 'icon2',
                    'name' => 'name2',
                    'symbol' => 'symbol2',
                    'rank' => 2,
                    'price' => 123.45,
                    'volume' => 123.45,
                    'marketCap' => 123.45,
                    'priceChange1d' => -12.34,
                ],
                [
                    'icon' => 'icon3',
                    'name' => 'name3',
                    'symbol' => 'symbol3',
                    'rank' => 3,
                    'price' => 123.45,
                    'volume' => 123.45,
                    'marketCap' => 123.45,
                    'priceChange1d' => -12.34,
                ],
            ]
        ];
    }

    public function fakeCoinGeneralInfoResponse(): array
    {
        return [
            'name' => 'name',
            'symbol' => 'symbol',
            'rank' => 1,
            'is_new' => false,
            'is_active' => true,
            'type' => 'type',
            'description' => 'description',
            'open_source' => true,
            'started_at' => '2000-01-01T00:00:00Z',
            'development_status' => 'development_status',
            'hardware_wallet' => true,
            'proof_type' => 'proof_type',
            'org_structure' => 'org_structure',
            'hash_algorithm' => 'hash_algorithm',
            'links_extended' => [
                [
                    'url' => 'url',
                    'type' => 'type',
                ],
                [
                    'url' => 'url',
                    'type' => 'type',
                    'stats' => [
                        'contributors' => 850,
                        'stars' => 42703,
                    ],
                ],
            ],
            'whitepaper' => [
                'link' => 'link',
                'thumbnail' => 'thumbnail'
            ],
        ];
    }

    public function fakeCoinMarketDataResponse(): array
    {
        return [
            'name' => 'name',
            'symbol' => 'symbol',
            'rank' => 1,
            'circulating_supply' => 12345,
            'max_supply' => 12345,
            'quotes' => [
                'USD' => [
                    'price' => 123.45,
                    'volume_24h' => 123.45,
                    'volume_24h_change_24h' => -12.45,
                    'market_cap' => 12345,
                    'market_cap_change_24h' => -12.34,
                    'percent_change_1h' => -12.34,
                    'percent_change_12h' => -12.34,
                    'percent_change_24h' => -12.34,
                    'percent_change_7d' => -12.34,
                    'percent_change_30d' => -12.34,
                    'percent_change_1y' => -12.34,
                ]
            ]
        ];
    }

    public function fakeCoinPriceByTimeResponse(): array
    {
        return [
            [
                'timestamp' => '2020-01-01T00:00:00Z',
                'price' => 123.45,
                'volume_24h' => 12345,
                'market_cap' => 12345,
            ],
            [
                'timestamp' => '2020-01-02T00:00:00Z',
                'price' => 123.45,
                'volume_24h' => 12345,
                'market_cap' => 12345,
            ],
            [
                'timestamp' => '2020-01-03T00:00:00Z',
                'price' => 123.45,
                'volume_24h' => 12345,
                'market_cap' => 12345,
            ],
        ];
    }

    public function fakeCoinOHLCVResponse(): array
    {
        return [
            'values' => [
                [
                    12345,
                    12345.12345,
                    12345.12345,
                    12345.12345,
                    12345.12345,
                    12345.12345
                ],
                [
                    12345,
                    12345.12345,
                    12345.12345,
                    12345.12345,
                    12345.12345,
                    12345.12345
                ],
                [
                    12345,
                    12345.12345,
                    12345.12345,
                    12345.12345,
                    12345.12345,
                    12345.12345
                ],
            ]
        ];
    }
}
