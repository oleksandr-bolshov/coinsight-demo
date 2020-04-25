<?php

declare(strict_types=1);

namespace App\Http\Users\Resources;

use App\Domain\Users\Entities\Session;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class SessionCollectionResource extends JsonResource implements Response
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
