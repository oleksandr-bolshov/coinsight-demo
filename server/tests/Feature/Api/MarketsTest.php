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
                        'change_24h',
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
}
