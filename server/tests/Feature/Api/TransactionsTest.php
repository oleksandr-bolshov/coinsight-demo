<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domain\Markets\Models\Coin;
use App\Domain\Portfolios\Enums\TransactionType;
use App\Domain\Portfolios\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Coinfo\CoinfoDataProvider;

final class TransactionsTest extends ApiTestCase
{
    use RefreshDatabase, CoinfoDataProvider;

    private int $coinId;
    private int $portfolioId;

    public function setUp(): void
    {
        parent::setUp();
        $this->fakeCoinfo();
        $this->coinId = factory(Coin::class)
            ->create([
                'name' => $this->currencyName(),
                'symbol' => $this->currencySymbol(),
            ])
            ->id;
        $this->portfolioId = factory(Portfolio::class)->create()->id;
    }

    public function test_create_transaction()
    {
        $response = $this->apiPost('/transactions', [
            'portfolio_id' => $this->portfolioId,
            'coin_id' => $this->coinId,
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

    public function test_get_transactions()
    {
        DB::table('transactions')->insert([
            'type' => TransactionType::BUY,
            'price_per_coin' => 1,
            'quantity' => 1,
            'fee' => 1,
            'datetime' => now(),
            'portfolio_id' => $this->portfolioId,
            'coin_id' => $this->coinId,
        ]);

        $response = $this->apiGet('/transactions', [
            'portfolio_id' => $this->portfolioId,
        ]);

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => [
                'transactions' => [
                    '*' => [
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
                ],
            ],
            'meta' => [],
        ]);
    }
}
