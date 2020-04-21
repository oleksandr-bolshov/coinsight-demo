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
}
