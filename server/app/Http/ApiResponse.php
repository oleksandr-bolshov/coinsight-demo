<?php

declare(strict_types=1);

namespace App\Http;

use App\Support\Contracts\ExceptionContract;
use App\Support\Contracts\ResponseContract;
use Illuminate\Http\JsonResponse;

final class ApiResponse extends JsonResponse
{
    public static function success(ResponseContract $response, array $meta = []): self
    {
        return new static([
            'data' => $response,
            'meta' => $meta
        ]);
    }

    public static function empty(): self
    {
        return new static();
    }

    public static function error(ExceptionContract $exception): self
    {
        return new static([
            'error' => $exception->toArray()
        ], $exception->getStatus());
    }
}
