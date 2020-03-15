<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Support\Contracts\ResponseContract;
use Illuminate\Http\Resources\Json\JsonResource;

final class TerminateSessionResource extends JsonResource implements ResponseContract
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
