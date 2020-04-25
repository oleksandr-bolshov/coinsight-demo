<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Coinfo\Client;
use App\Coinfo\Types\CoinOverview;
use App\Domain\Markets\Entities\Coin as CoinEntity;
use App\Domain\Markets\Entities\CoinOverview as CoinOverviewEntity;
use App\Domain\Markets\Models\Coin as CoinModel;
use App\Domain\Markets\Services\CoinService;

final class GetCoinsInteractor
{
    private Client $client;
    private CoinService $coinService;

    public function __construct(Client $client, CoinService $coinService)
    {
        $this->client = $client;
        $this->coinService = $coinService;
    }

    public function execute(GetCoinsRequest $request): GetCoinsResponse
    {
        $coinOverviewCollection = collect(
            $this->client->markets($request->page, $request->perPage)
        );

        $coinModelCollection = $this->coinService->getCollectionByNames(
            $coinOverviewCollection->pluck('name')->toArray()
        );

        $coinOverviewEntityCollection = collect();
        foreach ($coinOverviewCollection as $coinOverview) {
            $coinModel = $coinModelCollection->firstWhere('name', $coinOverview->name);

            if (!$coinModel) {
                continue;
            }

            $coinOverviewEntityCollection->push(
                $this->makeEntity($coinOverview, $coinModel)
            );
        }

        $total = CoinModel::count();

        return new GetCoinsResponse([
            'coins' => $coinOverviewEntityCollection,
            'total' => $total,
            'page' => $request->page,
            'perPage' => $request->perPage,
        ]);
    }

    private function makeEntity(CoinOverview $coinOverview, CoinModel $coinModel): CoinOverviewEntity
    {
        return new CoinOverviewEntity([
            'coin' => CoinEntity::fromModel($coinModel),
            'rank' => $coinOverview->rank,
            'price' => $coinOverview->price,
            'priceChange24h' => $coinOverview->change24h ?? null,
            'marketCap' => $coinOverview->marketCap,
            'volume' => $coinOverview->volume,
        ]);
    }
}
