<?php

declare(strict_types=1);

namespace Tests\Feature\Coinfo;

use App\Coinfo\Aggregators\CoinGecko;
use App\Coinfo\Aggregators\Coinpaprika;
use App\Coinfo\Aggregators\CoinStats;
use App\Coinfo\Aggregators\Messari;
use Illuminate\Support\Facades\Http;

trait CoinfoDataProvider
{
    public function currencyName(): string
    {
        return 'Currency Name';
    }

    public function currencySymbol(): string
    {
        return 'SYMBOL';
    }

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
                    'global',
                ),
                'response' => $this->fakeGlobalStatsResponse(),
            ],
            'markets' => [
                'url' => $this->getEndpointUrlWithWildcard(
                    CoinGecko::BASE_URL,
                    'coins/markets',
                ),
                'response' => $this->fakeCoinGeckoMarketsResponse(),
            ],
            'coinProfile' => [
                'url' => $this->getEndpointUrlWithWildcard(
                    Messari::BASE_URL,
                    'assets/currency-name/profile',
                ),
                'response' => $this->fakeCoinProfileResponse(),
            ],
            'coinMarketData' => [
                'url' => $this->getEndpointUrl(
                    Coinpaprika::BASE_URL,
                    'tickers/symbol-currency-name',
                ),
                'response' => $this->fakeCoinMarketDataResponse(),
            ],
            'coinHistoricalDataByDateRange' => [
                'url' => $this->getEndpointUrlWithWildcard(
                    Coinpaprika::BASE_URL,
                    'tickers/symbol-currency-name/historical',
                ),
                'response' => $this->fakeCoinHistoricalDataByDateRangeResponse(),
            ],
            'coinHistoricalData' => [
                'url' => $this->getEndpointUrlWithWildcard(
                    CoinGecko::BASE_URL,
                    '/coins/currency-name/market_chart',
                ),
                'response' => $this->fakeCoinHistoricalDataResponse(),
            ],
            'coinOHLCV' => [
                'url' => $this->getEndpointUrlWithWildcard(
                    Messari::BASE_URL,
                    'assets/currency-name/metrics/price/time-series',
                ),
                'response' => $this->fakeCoinOHLCVResponse(),
            ],
            '*' => [
                'url' => '*',
                'response' => ['fallback'],
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

    public function fakeCoinStatsMarketsResponse(): array
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

    public function fakeCoinGeckoMarketsResponse(): array
    {
        return [
            [
                'image' => 'icon1',
                'name' => 'name1',
                'symbol' => 'symbol1',
                'market_cap_rank' => 1,
                'current_price' => 1234.567,
                'total_volume' => 1234.567,
                'market_cap' => 1234.567,
                'price_change_percentage_24h' => -12.34,
            ],
            [
                'image' => 'icon2',
                'name' => 'name2',
                'symbol' => 'symbol2',
                'market_cap_rank' => 2,
                'current_price' => 123.45,
                'total_volume' => 123.45,
                'market_cap' => 123.45,
                'price_change_percentage_24h' => -12.34,
            ],
            [
                'image' => 'icon3',
                'name' => 'name3',
                'symbol' => 'symbol3',
                'market_cap_rank' => 3,
                'current_price' => 123.45,
                'total_volume' => 123.45,
                'market_cap' => 123.45,
                'price_change_percentage_24h' => -12.34,
            ],
        ];
    }

    public function fakeCoinProfileResponse(): array
    {
        return [
            'data' => [
                'name' => 'name',
                'symbol' => null,
                'profile' => [
                    'general' => [
                        'overview' => [
                            'tagline' => 'tagline',
                            'project_details' => 'project_details',
                            'official_links' => [
                                [
                                    'name' => 'name1',
                                    'link' => null,
                                ],
                                [
                                    'name' => 'name2',
                                    'link' => 'link',
                                ]
                            ]
                        ],
                    ],
                    'economics' => [
                        'token' => [
                            'token_type' => 'token_type',
                            'block_explorers' => null,
                        ],
                        'launch' => [
                            'initial_distribution' => [
                                'genesis_block_date' => '2009-01-03T09:00:00Z'
                            ]
                        ],
                        'consensus_and_emission' => [
                            'consensus' => [
                                'general_consensus_mechanism' => 'general_consensus_mechanism',
                                'mining_algorithm' => 'mining_algorithm'
                            ],
                        ],
                    ],
                ],
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

    public function fakeCoinHistoricalDataByDateRangeResponse(): array
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

    public function fakeCoinHistoricalDataResponse(): array
    {
        return [
            'prices' => [
                [
                    1586901867594,
                    123.45,
                ],
                [
                    1586901867594,
                    123.45,
                ],
                [
                    1586901867594,
                    123.45,
                ],
            ],
            'market_caps' => [
                [
                    1586901867594,
                    12345,
                ],
                [
                    1586901867594,
                    12345,
                ],
                [
                    1586901867594,
                    12345,
                ],
            ],
            'total_volumes' => [
                [
                    1586901867594,
                    12345,
                ],
                [
                    1586901867594,
                    12345,
                ],
                [
                    1586901867594,
                    12345,
                ],
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
