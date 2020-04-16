<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Coinfo\Client;
use App\Coinfo\Types\CoinOverview;
use App\Domain\Markets\Entities\Coin as CoinEntity;
use App\Domain\Markets\Models\Coin as CoinModel;

final class GetCoinsInteractor
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function execute(GetCoinsRequest $request): GetCoinsResponse
    {
        $coinOverviewCollection = collect(
            $this->client->markets($request->page, $request->perPage)
        );

        $coinsNames = $coinOverviewCollection->pluck('name');
        $coinModelCollection = CoinModel::whereIn('name', $coinsNames)->get();
        $total = CoinModel::count();

        $coinEntityCollection = collect();
        foreach ($coinOverviewCollection as $coinOverview) {
            $coinModel = $coinModelCollection->firstWhere('name', $coinOverview->name);

            if (!$coinModel) {
                continue;
            }

            $coinEntityCollection->push(
                $this->makeEntity($coinOverview, $coinModel)
            );
        }

        return new GetCoinsResponse([
            'coins' => $coinEntityCollection,
            'total' => $total,
            'page' => $request->page,
            'perPage' => $request->perPage,
        ]);
    }

    private function makeEntity(CoinOverview $coinOverview, CoinModel $coinModel): CoinEntity
    {
        return new CoinEntity([
            'id' => $coinModel->id,
            'name' => $coinModel->name,
            'symbol' => $coinModel->symbol,
            'icon' => $coinModel->icon,
            'rank' => $coinOverview->rank,
            'price' => $coinOverview->price,
            'priceChange24h' => $coinOverview->change24h ?? null,
            'marketCap' => $coinOverview->marketCap,
            'volume' => $coinOverview->volume,
        ]);
    }
}
