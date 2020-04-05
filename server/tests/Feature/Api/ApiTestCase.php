<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domain\Users\Models\Session;
use App\Domain\Users\Models\User;
use App\Http\Services\TokenService;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    protected User $user;
    protected Session $session;
    protected string $accessToken;

    private const API_PREFIX = 'api';

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->session = factory(Session::class)->create();
        $tokenService = $this->app->make(TokenService::class);
        $this->accessToken = $tokenService->generateAccessToken($this->session->id);
    }

    public function apiGet(string $endpoint, array $data = []): TestResponse
    {
        $uri = $this->getEndpointUri(self::API_PREFIX . $endpoint, $data);

        return parent::getJson($uri, [
            'Authorization' => 'Bearer ' . $this->accessToken
        ]);
    }

    private function getEndpointUri(string $endpoint, array $data): string
    {
        return $endpoint . '?' . http_build_query($data);
    }
}
