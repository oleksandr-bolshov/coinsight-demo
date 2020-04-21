<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Coinfo\Client;
use App\Coinfo\Types\CoinOverview;
use App\Domain\Markets\Entities\Coin as CoinEntity;
use App\Domain\Markets\Entities\CoinOverview as CoinOverviewEntity;
use App\Domain\Markets\Models\Coin as CoinModel;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

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

        $coinModelCollection = $this->getCoinWhereNames($coinOverviewCollection->pluck('name'));

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

    private function getCoinWhereNames(Collection $names): EloquentCollection
    {
        return CoinModel::whereIn('name', $names)->get();
    }
}
