<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class GlobalStatsResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'market_cap' => $this->marketCap,
            'market_cap_change' => $this->marketCapChange,
            'volume' => $this->volume,
            'volume_change' => $this->volumeChange,
            'bitcoin_dominance' => $this->bitcoinDominance,
        ];
    }
}
