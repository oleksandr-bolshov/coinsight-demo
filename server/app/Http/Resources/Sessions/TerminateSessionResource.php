<?php

declare(strict_types=1);

namespace App\Http\Resources\Sessions;

use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class TerminateSessionResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
