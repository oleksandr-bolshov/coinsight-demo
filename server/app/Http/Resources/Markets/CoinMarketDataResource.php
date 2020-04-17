<?php

declare(strict_types=1);

namespace App\Http\Resources\Markets;

use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class CoinMarketDataResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'id' =>  $this->id,
            'name' =>  $this->name,
            'symbol' =>  $this->symbol,
            'rank' =>  $this->rank,
            'circulating_supply' =>  $this->circulatingSupply,
            'max_supply' =>  $this->maxSupply,
            'price' =>  $this->price,
            'volume' =>  $this->volume,
            'volume_change_24h' =>  $this->volumeChange24h,
            'market_cap' =>  $this->marketCap,
            'market_cap_change_24h' =>  $this->marketCapChange24h,
            'price_change_1h' =>  $this->priceChange1h,
            'price_change_24h' =>  $this->priceChange24h,
            'price_change_7d' =>  $this->priceChange7d,
            'price_change_30d' =>  $this->priceChange30d,
            'price_change_1y' =>  $this->priceChange1y,
        ];
    }
}
