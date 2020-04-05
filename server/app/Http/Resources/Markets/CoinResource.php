<?php

declare(strict_types=1);

namespace App\Http\Resources\Markets;

use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class CoinResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'symbol' => $this->symbol,
            'icon' => $this->icon,
            'rank' => $this->rank,
            'price' => $this->price,
            'change_24h' => $this->change24h,
            'market_cap' => $this->marketCap,
            'volume' => $this->volume,
        ];
    }
}
