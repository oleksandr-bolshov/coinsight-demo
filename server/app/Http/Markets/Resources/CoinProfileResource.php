<?php

declare(strict_types=1);

namespace App\Http\Markets\Resources;

use App\Domain\Markets\Entities\CoinLink;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class CoinProfileResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'symbol' => $this->symbol,
            'icon' => url($this->icon),
            'tagline' => $this->tagline,
            'description' => $this->description,
            'type' => $this->type,
            'genesis_date' => $this->genesisDate,
            'consensus_mechanism' => $this->consensusMechanism,
            'hashing_algorithm' => $this->hashingAlgorithm,
            'links' => $this->links->map(fn (CoinLink $link) => [
                'type' => $link->type,
                'link' => $link->link,
            ]),
        ];
    }
}
