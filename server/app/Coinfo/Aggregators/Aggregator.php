<?php

declare(strict_types=1);

namespace App\Coinfo\Aggregators;

use App\Coinfo\Exceptions\AggregatorApiUrl;
use App\Coinfo\Exceptions\AggregatorRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

abstract class Aggregator
{
    public const BASE_URL = '';
    private const RATE_LIMIT_IN_SECONDS = 1;

    private float $lastRequestTimeInNanoseconds = 0;

    private function getEndpointUrl(string $endpoint): string
    {
        if (static::BASE_URL === '') {
            throw new AggregatorApiUrl('Aggregator api url is missing');
        }

        return trim(static::BASE_URL, '/') . '/' . trim($endpoint, '/');
    }

    protected function request(string $endpoint, array $query = []): array
    {
        $this->checkRateLimit();

        $response = Http::get($this->getEndpointUrl($endpoint), $query);

        try {
            $response->throw();
        } catch (RequestException $exception) {
            throw new AggregatorRequest($exception->getMessage());
        }

        return $response->json();
    }

    private function checkRateLimit(): void
    {
        if ($this->lastRequestTimeInNanoseconds === 0.0) {
            $this->lastRequestTimeInNanoseconds = hrtime(true);
            return;
        }

        $now = hrtime(true);

        $timeDifference = $now - $this->lastRequestTimeInNanoseconds;
        $rateLimitInNanoseconds = self::RATE_LIMIT_IN_SECONDS * 1e9;

        if ($timeDifference > $rateLimitInNanoseconds) {
            return;
        }

        $sleepTimeInMicroseconds = ($rateLimitInNanoseconds - $timeDifference) / 1e3;
        usleep((int) $sleepTimeInMicroseconds);
        $this->lastRequestTimeInNanoseconds = hrtime(true);
    }
}
