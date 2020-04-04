<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\Feature\Coinfo\CoinfoDataProvider;

final class MarketsTest extends ApiTestCase
{
    use RefreshDatabase, CoinfoDataProvider;

    private const URL_PREFIX = 'markets';

    public function setUp(): void
    {
        parent::setUp();
        $this->fakeCoinfo();
    }

    public function test_global_stats()
    {
        $response = $this->apiGet(self::URL_PREFIX .'/global');

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
}
