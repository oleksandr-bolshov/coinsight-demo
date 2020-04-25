<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Coinfo\Client;
use App\Domain\Markets\Entities\CoinMarketData;
use App\Domain\Markets\Services\CoinService;

final class GetMarketDataInteractor
{
    private Client $client;
    private CoinService $coinService;

    public function __construct(Client $client, CoinService $coinService)
    {
        $this->client = $client;
        $this->coinService = $coinService;
    }

    public function execute(GetMarketDataRequest $request): GetMarketDataResponse
    {
        $coin = $this->coinService->getById($request->id);

        $marketData = $this->client->coinMarketData($coin->name, $coin->symbol);

        $marketDataArray = ['id' => $request->id] + $marketData->toArray();

        return new GetMarketDataResponse([
            'marketData' => new CoinMarketData($marketDataArray)
        ]);
    }
}
