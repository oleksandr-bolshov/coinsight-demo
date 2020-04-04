<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\GlobalStats;

use App\Coinfo\Client;
use App\Domain\Markets\Entities\GlobalStats;

final class GetGlobalStatsInteractor
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function execute(): GetGlobalStatsResponse
    {
        $globalStats = $this->client->globalStats();
        return new GetGlobalStatsResponse([
            'globalStats' => new GlobalStats($globalStats->toArray())
        ]);
    }
}
