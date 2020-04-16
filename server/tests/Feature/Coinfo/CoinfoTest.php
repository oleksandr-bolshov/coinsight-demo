<?php

declare(strict_types=1);

namespace Tests\Feature\Coinfo;

use App\Coinfo\Client;
use App\Coinfo\Enums\Interval;
use Carbon\Carbon;
use Tests\TestCase;

final class CoinfoTest extends TestCase
{
    use CoinfoDataProvider;

    private Client $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = $this->app->make(Client::class);
        $this->fakeCoinfo();
    }

    public function test_global_stats()
    {
        $expectedResponse = $this->fakeGlobalStatsResponse();

        $globalStats = $this->client->globalStats();

        $this->assertEquals($expectedResponse['market_cap_usd'], $globalStats->marketCap);
        $this->assertEquals($expectedResponse['market_cap_change_24h'], $globalStats->marketCapChange);
        $this->assertEquals($expectedResponse['volume_24h_usd'], $globalStats->volume);
        $this->assertEquals($expectedResponse['volume_24h_change_24h'], $globalStats->volumeChange);
        $this->assertEquals($expectedResponse['bitcoin_dominance_percentage'], $globalStats->bitcoinDominance);
    }

    public function test_markets()
    {
        $expectedResponse = $this->fakeCoinGeckoMarketsResponse();

        $markets = $this->client->markets($page = 5, $perPage = 10);

        $this->assertCount(count($expectedResponse), $markets);

        for ($i = 0; $i < count($expectedResponse); $i++) {
            $this->assertEquals($expectedResponse[$i]['name'], $markets[$i]->name);
            $this->assertEquals($expectedResponse[$i]['symbol'], $markets[$i]->symbol);
            $this->assertEquals($expectedResponse[$i]['image'], $markets[$i]->icon);
            $this->assertEquals($expectedResponse[$i]['market_cap_rank'], $markets[$i]->rank);
            $this->assertEquals($expectedResponse[$i]['current_price'], $markets[$i]->price);
            $this->assertEquals($expectedResponse[$i]['price_change_percentage_24h'], $markets[$i]->change24h);
            $this->assertEquals($expectedResponse[$i]['market_cap'], $markets[$i]->marketCap);
            $this->assertEquals($expectedResponse[$i]['total_volume'], $markets[$i]->volume);
        }
    }

    public function test_coin_profile()
    {
        $expectedResponse = $this->fakeCoinProfileResponse();
        $expectedOverview = $expectedResponse['profile']['general']['overview'];
        $expectedEconomics = $expectedResponse['profile']['economics'];
        $expectedConsensus = $expectedResponse['profile']['economics']['consensus_and_emission']['consensus'];

        $profile = $this->client->coinProfile($this->currencyName());

        $this->assertEquals($expectedResponse['name'], $profile->name);
        $this->assertEquals($expectedResponse['symbol'], $profile->symbol);

        $this->assertEquals($expectedOverview['tagline'], $profile->tagline);
        $this->assertEquals($expectedOverview['project_details'], $profile->description);
        $this->assertEquals($expectedEconomics['token']['token_type'], $profile->type);
        $this->assertTrue(
            Carbon::parse($expectedEconomics['launch']['initial_distribution']['genesis_block_date'])
                ->equalTo($profile->genesisDate)
        );
        $this->assertEquals($expectedConsensus['general_consensus_mechanism'], $profile->consensusMechanism);
        $this->assertEquals($expectedConsensus['mining_algorithm'], $profile->hashingAlgorithm);

        $this->assertCount($notNullLinksCount = 1, $profile->links);
        $this->assertEquals($expectedOverview['official_links'][1]['name'], $profile->links[0]->type);
        $this->assertEquals($expectedOverview['official_links'][1]['link'], $profile->links[0]->link);

        $this->assertEquals($expectedEconomics['token']['block_explorers'], $profile->blockExplorers);

    }

    public function test_coin_market_data()
    {
        $expectedResponse = $this->fakeCoinMarketDataResponse();

        $marketData = $this->client->coinMarketData($this->currencyName(), $this->currencySymbol());

        $this->assertEquals($expectedResponse['name'], $marketData->name);
        $this->assertEquals($expectedResponse['symbol'], $marketData->symbol);
        $this->assertEquals($expectedResponse['rank'], $marketData->rank);
        $this->assertEquals($expectedResponse['circulating_supply'], $marketData->circulatingSupply);
        $this->assertEquals($expectedResponse['max_supply'], $marketData->maxSupply);

        $quote = $expectedResponse['quotes']['USD'];
        $this->assertEquals($quote['price'], $marketData->price);
        $this->assertEquals($quote['volume_24h'], $marketData->volume);
        $this->assertEquals($quote['volume_24h_change_24h'], $marketData->volumeChange);
        $this->assertEquals($quote['market_cap'], $marketData->marketCap);
        $this->assertEquals($quote['market_cap_change_24h'], $marketData->marketCapChange);
        $this->assertEquals($quote['percent_change_1h'], $marketData->change1h);
        $this->assertEquals($quote['percent_change_24h'], $marketData->change24h);
        $this->assertEquals($quote['percent_change_7d'], $marketData->change7d);
        $this->assertEquals($quote['percent_change_30d'], $marketData->change30d);
        $this->assertEquals($quote['percent_change_1y'], $marketData->change1y);
    }

    public function test_coin_price_by_time_range()
    {
        $expectedResponse = $this->fakeCoinPriceByTimeRangeResponse();

        $priceByTimeRange = $this->client->coinPriceByTimeRange(
            $this->currencyName(),
            $this->currencySymbol(),
            $start = $now = Carbon::now(),
            $end = $now->subDay(),
            $limit = 10,
            Interval::FIFTEEN_MINUTES()
        );

        $this->assertCount(count($expectedResponse), $priceByTimeRange);

        for ($i = 0; $i < count($expectedResponse); $i++) {
            $this->assertTrue(
                Carbon::parse($expectedResponse[$i]['timestamp'])->equalTo($priceByTimeRange[$i]->timestamp)
            );
            $this->assertEquals($expectedResponse[$i]['price'], $priceByTimeRange[$i]->price);
            $this->assertEquals($expectedResponse[$i]['volume_24h'], $priceByTimeRange[$i]->volume);
            $this->assertEquals($expectedResponse[$i]['market_cap'], $priceByTimeRange[$i]->marketCap);
        }

    }

    public function test_coin_price_by_time()
    {
        $expectedResponse = $this->fakeCoinPriceByTimeResponse();

        $priceByTime = $this->client->coinPriceByTime($this->currencyName(), $days = 3);

        $this->assertCount(count($expectedResponse['prices']), $priceByTime);

        for ($i = 0; $i < count($priceByTime); $i++) {
            $this->assertTrue(
                Carbon::parse($expectedResponse['prices'][$i][0])->equalTo($priceByTime[$i]->timestamp)
            );
            $this->assertEquals($expectedResponse['prices'][$i][1], $priceByTime[$i]->price);
            $this->assertEquals($expectedResponse['market_caps'][$i][1], $priceByTime[$i]->marketCap);
            $this->assertEquals($expectedResponse['total_volumes'][$i][1], $priceByTime[$i]->volume);

        }
    }

    public function test_coin_OHLCV()
    {
        $expectedResponse = $this->fakeCoinOHLCVResponse()['values'];

        $ohlcv = $this->client->coinOHLCV(
            $this->currencyName(),
            $start = $now = Carbon::now(),
            $end = $now->subDay(),
            Interval::ONE_DAY()
        );

        $this->assertCount(count($expectedResponse), $ohlcv);

        for ($i = 0; $i < count($expectedResponse); $i++) {
            $this->assertEquals($expectedResponse[$i][0], $ohlcv[$i]->timestamp);
            $this->assertEquals($expectedResponse[$i][1], $ohlcv[$i]->open);
            $this->assertEquals($expectedResponse[$i][2], $ohlcv[$i]->high);
            $this->assertEquals($expectedResponse[$i][3], $ohlcv[$i]->low);
            $this->assertEquals($expectedResponse[$i][4], $ohlcv[$i]->close);
            $this->assertEquals($expectedResponse[$i][5], $ohlcv[$i]->volume);
        }
    }
}
