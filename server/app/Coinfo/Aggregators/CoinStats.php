<?php

declare(strict_types=1);

namespace App\Coinfo\Aggregators;

use App\Coinfo\Factories\CoinStats\CoinCollectionFactory;
use App\Coinfo\Types\CoinOverviewCollection;

final class CoinStats extends Aggregator
{
    public const BASE_URL = 'https://api.coinstats.app/public/v1/';

    public function coins(int $skip = 0, int $limit = 5): CoinOverviewCollection
    {
        $data = $this->request('coins', [
            'skip' => $skip,
            'limit' => $limit,
        ]);

        return CoinCollectionFactory::create($data);
    }
}
