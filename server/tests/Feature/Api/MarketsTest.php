<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Coinfo\CoinfoDataProvider;

final class MarketsTest extends ApiTestCase
{
    use RefreshDatabase, CoinfoDataProvider;

    public function setUp(): void
    {
        parent::setUp();
        $this->fakeCoinfo();
    }

    public function test_global_stats()
    {
        $response = $this->apiGet('/global');

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => [
                'market_cap',
                'volume',
                'bitcoin_dominance',
                'market_cap_change',
                'volume_change',
            ],
            'meta' => [],
        ]);
    }

    public function test_coins()
    {
        DB::table('coins')->insert([
            [
                'name' => 'name1',
                'symbol' => 'symbol1',
                'icon' => 'icon1',
            ],
            [
                'name' => 'name2',
                'symbol' => 'symbol2',
                'icon' => 'icon2',
            ],
            [
                'name' => 'name3',
                'symbol' => 'symbol3',
                'icon' => 'icon3',
            ],
        ]);

        $response = $this->apiGet('/coins', [
            'page' => 1,
            'per_page' => 5
        ]);

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => [
                'coins' => [
                    '*' => [
                        'id',
                        'name',
                        'symbol',
                        'icon',
                        'rank',
                        'price',
                        'price_change_24h',
                        'market_cap',
                        'volume',
                    ]
                ]
            ],
            'meta' => [
                'total',
                'page',
                'per_page',
            ]
        ]);
    }

    public function test_profile()
    {
        $coinId = DB::table('coins')->insertGetId([
            'name' => 'currency name',
            'symbol' => 'symbol',
        ]);

        $response = $this->apiGet("/coins/{$coinId}/profile");

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'symbol',
                'icon',
                'tagline',
                'description',
                'type',
                'genesis_date',
                'consensus_mechanism',
                'hashing_algorithm',
                'links' => [
                    '*' => [
                        'type',
                        'link',
                    ]
                ],
            ],
            'meta' => []
        ]);
    }

    public function test_market_data()
    {
        $coinId = DB::table('coins')->insertGetId([
            'name' => 'currency name',
            'symbol' => 'symbol',
        ]);

        $response = $this->apiGet("/coins/{$coinId}/latest");

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'symbol',
                'rank',
                'circulating_supply',
                'max_supply',
                'price',
                'volume',
                'volume_change_24h',
                'market_cap',
                'market_cap_change_24h',
                'price_change_1h',
                'price_change_24h',
                'price_change_7d',
                'price_change_30d',
                'price_change_1y',
            ],
            'meta' => []
        ]);
    }

    public function test_historical_data()
    {
        DB::table('coins')->insert([
            'name' => 'currency name',
            'symbol' => 'symbol',
        ]);

        $coinId = 1;

        $response = $this->apiGet("/coins/{$coinId}/historical", [
            'period' => '1w'
        ]);

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => [
                'historical_data' => [
                    '*' => [
                        'timestamp',
                        'price',
                        'market_cap',
                        'volume',
                    ],
                ],
            ],
            'meta' => []
        ]);
    }
}
