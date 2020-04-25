<?php

declare(strict_types=1);

namespace App\Http\Markets\Controllers;

use App\Domain\Markets\Interactors\GlobalStats\GetGlobalStatsInteractor;
use App\Http\Markets\Resources\GlobalStatsResource;
use App\Http\Common\ApiResponse;

final class GlobalStatsController
{
    public function getGlobalStats(GetGlobalStatsInteractor $globalStatsInteractor): ApiResponse
    {
        $globalStats = $globalStatsInteractor->execute()->globalStats;
        return ApiResponse::success(new GlobalStatsResource($globalStats));
    }
}
