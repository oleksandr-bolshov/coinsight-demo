<?php

declare(strict_types=1);

namespace App\Http\Users\Resources;

use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class LoginResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
        ];
    }
}
