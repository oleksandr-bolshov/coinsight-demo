<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domain\Users\Models\Session;
use App\Domain\Users\Services\TokenService;
use Illuminate\Http\Response;

final class SessionsTest extends ApiTestCase
{
    public function test_get_sessions()
    {
        $this
            ->apiGet('sessions')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'sessions' => [
                        '*' => [
                            'id',
                            'user_id',
                            'created_at',
                            'last_used_at',
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

    public function test_get_access_token()
    {
        $tokenService = $this->app->make(TokenService::class);
        $refreshToken = $tokenService->generateRefreshToken($this->session->id);
        $this->headers['Authorization'] = 'Bearer ' . $refreshToken;

        $this
            ->apiGet('sessions/access-token')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'access_token'
                ],
            ]);
    }

    public function test_terminate()
    {
        $session = factory(Session::class)->create();

        $this
            ->apiPut('sessions/terminate', [
                'id' => $session->id,
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id' => $session->id,
                ],
            ]);
    }
}
