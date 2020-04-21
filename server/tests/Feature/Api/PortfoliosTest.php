<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

final class PortfoliosTest extends ApiTestCase
{
    use RefreshDatabase;

    public function test_create_portfolio()
    {
        $response = $this->apiPost('/portfolios', [
            'name' => 'portfolio name',
        ]);

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ],
            'meta' => [],
        ]);
    }

    public function test_get_portfolios()
    {
        $response = $this->apiGet('/portfolios');

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => [
                'portfolios' => [
                    '*' => [
                        'id',
                        'name',
                    ],
                ],
            ],
            'meta' => [],
        ]);
    }

    public function test_get_portfolios_when_no_portfolios()
    {
        $response = $this->apiGet('/portfolios');

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => [],
            'meta' => [],
        ]);
    }
}
