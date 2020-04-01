<?php

declare(strict_types=1);

namespace App\Coinfo\Aggregators;

use App\Coinfo\Enums\Interval;
use App\Coinfo\Factories\Messari\CoinOHLCVCollectionFactory;
use App\Coinfo\Types\CoinOHLCVCollection;
use Carbon\Carbon;

final class Messari extends Aggregator
{
    public const BASE_URL = 'https://data.messari.io/api/v1/';

    public function assetTimeseries(
        string $asset,
        ?Carbon $start = null,
        ?Carbon $end = null,
        ?Interval $interval = null
    ): CoinOHLCVCollection {
        $end ??= $now = Carbon::now();
        $start ??= $now->subDay();
        $interval ??= Interval::FIVE_MINUTES;

        $data = $this->request("assets/{$asset}/metrics/price/time-series", [
            'start' => $start,
            'end' => $end,
            'interval' => $interval,
        ]);

        return CoinOHLCVCollectionFactory::create($data);
    }
}
