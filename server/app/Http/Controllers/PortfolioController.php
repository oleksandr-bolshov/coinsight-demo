<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Portfolios\Interactors\Portfolios\CreatePortfolioInteractor;
use App\Domain\Portfolios\Interactors\Portfolios\CreatePortfolioRequest;
use App\Domain\Portfolios\Interactors\Portfolios\GetPortfoliosInteractor;
use App\Domain\Portfolios\Interactors\Portfolios\GetPortfoliosRequest;
use App\Http\ApiResponse;
use App\Http\Requests\DefaultRequest;
use App\Http\Requests\Portfolios\CreatePortfolioApiRequest;
use App\Http\Resources\Portfolios\PortfolioCollectionResource;
use App\Http\Resources\Portfolios\PortfolioResource;

final class PortfolioController
{
    public function createPortfolio(
        CreatePortfolioApiRequest $request,
        CreatePortfolioInteractor $createPortfolioInteractor
    ): ApiResponse {
        $portfolio = $createPortfolioInteractor
            ->execute(new CreatePortfolioRequest([
                'name' => $request->name(),
                'userId' => $request->userId(),
            ]))
            ->portfolio;

        return ApiResponse::success(new PortfolioResource($portfolio));
    }

    public function getPortfolios(
        DefaultRequest $request,
        GetPortfoliosInteractor $portfoliosInteractor
    ): ApiResponse {
        $portfolios = $portfoliosInteractor
            ->execute(new GetPortfoliosRequest([
                'userId' => $request->userId(),
            ]))
            ->portfolios;

        return ApiResponse::success(new PortfolioCollectionResource($portfolios));
    }
}
