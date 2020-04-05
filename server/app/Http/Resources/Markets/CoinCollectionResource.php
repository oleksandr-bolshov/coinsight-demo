<?php

declare(strict_types=1);

namespace App\Http\Resources\Markets;

use App\Domain\Markets\Entities\Coin;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class CoinCollectionResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'coins' => $this->map(
                fn (Coin $coin) => new CoinResource($coin)
            ),
        ];
    }
}
