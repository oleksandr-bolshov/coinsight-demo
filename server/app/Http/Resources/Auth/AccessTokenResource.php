<?php

declare(strict_types=1);

namespace App\Http\Resources\Auth;

use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class AccessTokenResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'access_token' => $this->accessToken,
        ];
    }
}
