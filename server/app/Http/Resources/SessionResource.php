<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Support\Contracts\ResponseContract;
use Illuminate\Http\Resources\Json\JsonResource;

final class SessionResource extends JsonResource implements ResponseContract
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'created_at' => $this->createdAt,
            'last_used_at' => $this->lastUsedAt,
        ];
    }
}
