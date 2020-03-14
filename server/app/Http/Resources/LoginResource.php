<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Support\Contracts\ResponseContract;
use Illuminate\Http\Resources\Json\JsonResource;

final class LoginResource extends JsonResource implements ResponseContract
{
    public function toArray($request): array
    {
        return [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
        ];
    }
}
