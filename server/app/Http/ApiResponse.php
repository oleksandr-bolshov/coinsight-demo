<?php

declare(strict_types=1);

namespace App\Http;

use App\Contracts\ExceptionContract as ApiExceptionContract;
use App\Contracts\ResponseContract as ApiResponseContract;
use Illuminate\Http\JsonResponse;

final class ApiResponse extends JsonResponse
{
    public static function success(ApiResponseContract $response, array $meta = []): self
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

    public static function error(ApiExceptionContract $exception): self
    {
        return new static([
            'error' => $exception->toArray()
        ], $exception->getStatus());
    }
}
