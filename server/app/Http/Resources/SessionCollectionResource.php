<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Users\Entities\Session;
use App\Support\Contracts\ResponseContract;
use Illuminate\Http\Resources\Json\JsonResource;

final class SessionCollectionResource extends JsonResource implements ResponseContract
{
    public function toArray($request): array
    {
        $sessionResourceCollection = $this->sessions->map(
            fn (Session $session) => new SessionResource($session)
        );

        return [
            'sessions' => $sessionResourceCollection
        ];
    }
}
