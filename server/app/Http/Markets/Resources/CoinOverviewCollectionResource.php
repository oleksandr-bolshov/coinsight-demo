<?php

declare(strict_types=1);

namespace App\Http\Markets\Resources;

use App\Domain\Markets\Entities\CoinOverview;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class CoinOverviewCollectionResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        $coins = $this->map(
            fn (CoinOverview $overview) => [
                'id' => $overview->coin->id,
                'name' => $overview->coin->name,
                'symbol' => $overview->coin->symbol,
                'icon' => url($overview->coin->icon),
                'rank' => $overview->rank,
                'price' => $overview->price,
                'price_change_24h' => $overview->priceChange24h,
                'market_cap' => $overview->marketCap,
                'volume' => $overview->volume,
            ],
        );


        return [
            'coins' => $coins,
        ];
    }
}
