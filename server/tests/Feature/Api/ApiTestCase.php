<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domain\Users\Models\Session;
use App\Domain\Users\Models\User;
use App\Domain\Users\Services\TokenService;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    protected User $user;
    protected Session $session;
    protected string $accessToken;
    protected array $headers = [];

    private const API_PREFIX = 'api';

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->session = factory(Session::class)->create();
        $tokenService = $this->app->make(TokenService::class);
        $this->accessToken = $tokenService->generateAccessToken($this->session->id);
        $this->headers['Authorization'] = 'Bearer ' . $this->accessToken;
    }

    public function apiGet(string $endpoint, array $data = []): TestResponse
    {
        $uri = $this->getEndpointUri($endpoint, $data);

        return parent::getJson($uri, $this->headers);
    }

    public function apiPost(string $endpoint, array $data): TestResponse
    {
        $uri = $this->getEndpointUri($endpoint);

        return parent::postJson($uri, $data, $this->headers);
    }

    private function getEndpointUri(string $endpoint, array $data = []): string
    {
        $uri = trim(self::API_PREFIX, '/') . '/' . trim($endpoint, '/');

        if (empty($data)) {
            return $uri;
        }

        return $uri . '?' . http_build_query($data);
    }
}
