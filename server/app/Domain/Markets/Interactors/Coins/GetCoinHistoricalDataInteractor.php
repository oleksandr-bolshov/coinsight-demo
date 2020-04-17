<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Coinfo\Client;
use App\Domain\Markets\Entities\CoinHistoricalData;
use App\Domain\Markets\Enums\ChartDays;
use App\Domain\Markets\Exceptions\CoinNotFound;
use App\Domain\Markets\Models\Coin as CoinModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class GetHistoricalDataInteractor
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function execute(GetHistoricalDataRequest $request): GetHistoricalDataResponse
    {
        try {
            $coin = CoinModel::findOrFail($request->id);
        } catch (ModelNotFoundException $exception) {
            throw new CoinNotFound();
        }

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
