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
    private const TEST_CURRENCY_NAME = 'Currency Name';
    private const TEST_CURRENCY_SYMBOL = 'SYMBOL';

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
        $expectedResponse = $this->fakeMarketsResponse()['coins'];

        $markets = $this->client->markets($skip = 5, $limit = 10);

        $this->assertCount(count($expectedResponse), $markets);

        for ($i = 0; $i < count($expectedResponse); $i++) {
            $this->assertEquals($expectedResponse[$i]['name'], $markets[$i]->name);
            $this->assertEquals($expectedResponse[$i]['symbol'], $markets[$i]->symbol);
            $this->assertEquals($expectedResponse[$i]['icon'], $markets[$i]->icon);
            $this->assertEquals($expectedResponse[$i]['rank'], $markets[$i]->rank);
            $this->assertEquals($expectedResponse[$i]['price'], $markets[$i]->price);
            $this->assertEquals($expectedResponse[$i]['priceChange1d'], $markets[$i]->change24h);
            $this->assertEquals($expectedResponse[$i]['marketCap'], $markets[$i]->marketCap);
            $this->assertEquals($expectedResponse[$i]['volume'], $markets[$i]->volume);
        }
    }

    public function test_coin_general_info()
    {
        $expectedResponse = $this->fakeCoinGeneralInfoResponse();

        $generalInfo = $this->client->coinGeneralInfo(self::TEST_CURRENCY_NAME, self::TEST_CURRENCY_SYMBOL);

        $this->assertEquals($expectedResponse['name'], $generalInfo->name);
        $this->assertEquals($expectedResponse['symbol'], $generalInfo->symbol);
        $this->assertEquals($expectedResponse['rank'], $generalInfo->rank);
        $this->assertEquals($expectedResponse['is_new'], $generalInfo->isNew);
        $this->assertEquals($expectedResponse['is_active'], $generalInfo->isActive);
        $this->assertEquals($expectedResponse['type'], $generalInfo->type);
        $this->assertEquals($expectedResponse['description'], $generalInfo->description);
        $this->assertEquals($expectedResponse['open_source'], $generalInfo->isOpenSource);
        $this->assertTrue(Carbon::parse($expectedResponse['started_at'])->equalTo($generalInfo->startedAt));
        $this->assertEquals($expectedResponse['development_status'], $generalInfo->developmentStatus);
        $this->assertEquals($expectedResponse['hardware_wallet'], $generalInfo->hasHardwareWallet);
        $this->assertEquals($expectedResponse['proof_type'], $generalInfo->proofType);
        $this->assertEquals($expectedResponse['org_structure'], $generalInfo->orgStructure);
        $this->assertEquals($expectedResponse['hash_algorithm'], $generalInfo->hashAlgorithm);

        for ($i = 0; $i < count($expectedResponse['links_extended']); $i++) {
            $this->assertEquals($expectedResponse['links_extended'][$i]['url'], $generalInfo->links[$i]->url);
            $this->assertEquals($expectedResponse['links_extended'][$i]['type'], $generalInfo->links[$i]->type);
        }

        $this->assertEquals($expectedResponse['whitepaper']['link'], $generalInfo->whitepaper->link);
        $this->assertEquals($expectedResponse['whitepaper']['thumbnail'], $generalInfo->whitepaper->thumbnail);
    }

    public function test_coin_market_data()
    {
        $expectedResponse = $this->fakeCoinMarketDataResponse();

        $marketData = $this->client->coinMarketData(self::TEST_CURRENCY_NAME, self::TEST_CURRENCY_SYMBOL);

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
        $this->assertEquals($quote['percent_change_12h'], $marketData->change12h);
        $this->assertEquals($quote['percent_change_24h'], $marketData->change24h);
        $this->assertEquals($quote['percent_change_7d'], $marketData->change7d);
        $this->assertEquals($quote['percent_change_30d'], $marketData->change30d);
        $this->assertEquals($quote['percent_change_1y'], $marketData->change1y);
    }

    public function test_coin_price_by_time()
    {
        $expectedResponse = $this->fakeCoinPriceByTimeResponse();

        $priceByTime = $this->client->coinPriceByTime(
            self::TEST_CURRENCY_NAME,
            self::TEST_CURRENCY_SYMBOL,
            $start = $now = Carbon::now(),
            $end = $now->subDay(),
            $limit = 10,
            Interval::FIFTEEN_MINUTES()
        );

        $this->assertCount(count($expectedResponse), $priceByTime);

        for ($i = 0; $i < count($expectedResponse); $i++) {
            $this->assertTrue(Carbon::parse($expectedResponse[$i]['timestamp'])->equalTo($priceByTime[$i]->timestamp));
            $this->assertEquals($expectedResponse[$i]['price'], $priceByTime[$i]->price);
            $this->assertEquals($expectedResponse[$i]['volume_24h'], $priceByTime[$i]->volume);
            $this->assertEquals($expectedResponse[$i]['market_cap'], $priceByTime[$i]->marketCap);
        }

    }

    public function test_coin_OHLCV()
    {
        $expectedResponse = $this->fakeCoinOHLCVResponse()['values'];

        $ohlcv = $this->client->coinOHLCV(
            self::TEST_CURRENCY_NAME,
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
