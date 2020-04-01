<?php

declare(strict_types=1);

namespace App\Coinfo\Factories\Messari;

use App\Coinfo\Types\CoinOHLCV;
use App\Coinfo\Types\CoinOHLCVCollection;

final class CoinOHLCVCollectionFactory
{
    public static function create(array $data): CoinOHLCVCollection
    {
        $arrayOfOHLCV = array_map(
            fn ($ohlcv) => new CoinOHLCV([
                'timestamp' => $ohlcv[0],
                'open' => $ohlcv[1],
                'high' => $ohlcv[2],
                'low' => $ohlcv[3],
                'close' => $ohlcv[4],
                'volume' => $ohlcv[5]
            ]),
            $data['values']
        );
        return new CoinOHLCVCollection($arrayOfOHLCV);
    }
}
