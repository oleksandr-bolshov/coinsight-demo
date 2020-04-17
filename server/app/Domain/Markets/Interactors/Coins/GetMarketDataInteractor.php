<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Coinfo\Client;
use App\Domain\Markets\Entities\CoinMarketData;
use App\Domain\Markets\Exceptions\CoinNotFound;
use App\Domain\Markets\Models\Coin as CoinModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class GetMarketDataInteractor
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function execute(GetMarketDataRequest $request): GetMarketDataResponse
    {
        try {
            $coin = CoinModel::findOrFail($request->id);
        } catch (ModelNotFoundException $exception) {
            throw new CoinNotFound();
        }

        $marketData = $this->client->coinMarketData($coin->name, $coin->symbol);

        $marketDataArray = ['id' => $request->id] + $marketData->toArray();

        return new GetMarketDataResponse([
            'marketData' => new CoinMarketData($marketDataArray)
        ]);
    }
}
