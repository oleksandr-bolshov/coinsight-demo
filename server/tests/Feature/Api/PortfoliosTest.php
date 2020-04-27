<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domain\Markets\Models\Coin;
use App\Domain\Portfolios\Models\Portfolio;
use App\Domain\Portfolios\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\Feature\Coinfo\CoinfoDataProvider;

final class PortfoliosTest extends ApiTestCase
{
    use RefreshDatabase, CoinfoDataProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fakeCoinfo();
    }

    public function test_create_portfolio()
    {
        $this
            ->apiPost('/portfolios', [
                'name' => 'portfolio name',
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                ],
            ]);
    }

    public function test_get_portfolios()
    {
        factory(Portfolio::class)->create();

        $this
            ->apiGet('/portfolios')
            ->dump()
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'portfolios' => [
                        '*' => [
                            'id',
                            'name',
                        ],
                    ],
                ],
                'meta' => [
                    'total',
                    'page',
                    'per_page',
                    'last_page',
                ],
            ]);
    }

    public function test_get_portfolios_when_no_portfolios()
    {
        $this
            ->apiGet('/portfolios')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [],
                'meta' => [],
            ]);
    }

    public function test_get_portfolio_report()
    {
        $portfolioId = factory(Portfolio::class)->create()->id;
        $coinId = factory(Coin::class)->create([
            'name' => $this->currencyName(),
            'symbol' => $this->currencySymbol(),
        ])->id;
        factory(Transaction::class)->create([
            'portfolio_id' => $portfolioId,
            'coin_id' => $coinId,
        ]);

        $this
            ->apiGet("/portfolios/{$portfolioId}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'portfolio',
                    'total_value',
                    'total_value_change',
                    'assets' => [
                        '*' => [
                            'coin',
                            'price',
                            'price_change_24h',
                            'holdings',
                            'market_value',
                            'net_cost',
                            'net_profit',
                            'percent_change',
                            'share',
                        ],
                    ],
                ],
            ]);
    }
}
