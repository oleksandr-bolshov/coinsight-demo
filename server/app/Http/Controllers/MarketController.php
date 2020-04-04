<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Markets\Interactors\GlobalStats\GetGlobalStatsInteractor;
use App\Http\ApiResponse;
use App\Http\Resources\Markets\GlobalStatsResource;

final class MarketController
{
    public function getGlobalStats(GetGlobalStatsInteractor $globalStatsInteractor): ApiResponse
    {
        $globalStats = $globalStatsInteractor->execute()->globalStats;
        return ApiResponse::success(new GlobalStatsResource($globalStats));
    }
}
