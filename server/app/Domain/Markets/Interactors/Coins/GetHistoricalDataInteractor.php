<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Coinfo\Client;
use App\Domain\Markets\Entities\CoinHistoricalData;
use App\Domain\Markets\Enums\ChartDays;
use App\Domain\Markets\Services\CoinService;

final class GetHistoricalDataInteractor
{
    private Client $client;
    private CoinService $coinService;

    public function __construct(Client $client, CoinService $coinService)
    {
        $this->client = $client;
        $this->coinService = $coinService;
    }

    public function execute(GetHistoricalDataRequest $request): GetHistoricalDataResponse
    {
        $coin = $this->coinService->getById($request->id);

        if ($request->days->is(ChartDays::MAX)) {
            $historicalDataResponse = $this->client->coinHistoricalDataAllTime($coin->name);
        } else {
            $historicalDataResponse = $this->client->coinHistoricalData($coin->name, $request->days->value);
        }

        $historicalData = collect($historicalDataResponse)->map(
            fn ($historicalDataItem) => new CoinHistoricalData($historicalDataItem->toArray())
        );

        return new GetHistoricalDataResponse([
            'historicalData' => $historicalData,
        ]);
    }
}
