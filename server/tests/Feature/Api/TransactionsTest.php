<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domain\Markets\Models\Coin;
use App\Domain\Portfolios\Enums\TransactionType;
use App\Domain\Portfolios\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\Feature\Coinfo\CoinfoDataProvider;

final class TransactionsTest extends ApiTestCase
{
    use RefreshDatabase, CoinfoDataProvider;

    public function setUp(): void
    {
        parent::setUp();
        $this->fakeCoinfo();
    }

    public function test_create_transaction()
    {
        $coinId = factory(Coin::class)->create()->id;
        $portfolioId = factory(Portfolio::class)->create()->id;

        $response = $this->apiPost('/transactions', [
            'portfolio_id' => $portfolioId,
            'coin_id' => $coinId,
            'type' => TransactionType::BUY,
            'price_per_coin' => 1,
            'quantity' => 1,
            'fee' => 1,
            'datetime' => now(),
        ]);

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => [
                'id',
                'coin' => [
                    'id',
                    'name',
                    'symbol',
                    'icon',
                ],
                'type',
                'price_per_coin',
                'quantity',
                'fee',
                'cost',
                'current_value',
                'value_change',
                'datetime',
            ],
            'meta' => [],
        ]);
    }
}
