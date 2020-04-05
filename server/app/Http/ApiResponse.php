<?php

declare(strict_types=1);

namespace App\Http;

use App\Support\Contracts\Exception;
use App\Support\Contracts\Response;
use Illuminate\Http\JsonResponse;

final class ApiResponse extends JsonResponse
{
    public static function success(Response $response, array $meta = []): self
    {
        return new static([
            'data' => $response,
            'meta' => $meta,
        ]);
    }

    public static function empty(): self
    {
        return new static([
            'data' => [],
            'meta' => [],
        ]);
    }

    public static function error(Exception $exception): self
    {
        return new static([
            'error' => $exception->toArray()
        ], $exception->getStatus());
    }
}
