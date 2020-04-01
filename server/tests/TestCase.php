<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function getEndpointUrl(string $base, string $endpoint): string
    {
        return trim($base, '/') . '/' . trim($endpoint, '/');
    }

    protected function getEndpointUrlWithWildcard(string $base, string $endpoint): string
    {
        return $this->getEndpointUrl($base, $endpoint) . '*';
    }
}
