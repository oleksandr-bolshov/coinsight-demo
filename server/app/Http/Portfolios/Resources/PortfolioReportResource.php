<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Resources;

use App\Domain\Portfolios\Entities\Asset;
use App\Http\Markets\Resources\CoinResource;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class PortfolioReportResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'portfolio' => new PortfolioResource($this->portfolio),
            'total_value' => $this->totalValue,
            'total_value_change' => $this->totalValueChange,
            'assets' => $this->assets->map(fn (Asset $asset) => [
                'coin' => new CoinResource($asset->coin),
                'price' => $asset->price,
                'price_change_24h' => $asset->priceChange24h,
                'holdings' => $asset->holdings,
                'market_value' => $asset->marketValue,
                'net_cost' => $asset->netCost,
                'net_profit' => $asset->netProfit,
                'percent_change' => $asset->valueChange,
                'share' => $asset->share,
            ]),
        ];
    }
}
