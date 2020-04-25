<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Controllers;

use App\Domain\Portfolios\Interactors\Portfolios\CreatePortfolioInteractor;
use App\Domain\Portfolios\Interactors\Portfolios\CreatePortfolioRequest;
use App\Domain\Portfolios\Interactors\Portfolios\GetPortfoliosInteractor;
use App\Domain\Portfolios\Interactors\Portfolios\GetPortfoliosRequest;
use App\Http\Portfolios\Requests\CreatePortfolioApiRequest;
use App\Http\Portfolios\Requests\GetPortfoliosApiRequest;
use App\Http\Portfolios\Resources\PortfolioCollectionResource;
use App\Http\Portfolios\Resources\PortfolioResource;
use App\Http\Common\ApiResponse;
use App\Http\Common\Requests\DefaultRequest;

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
        GetPortfoliosApiRequest $request,
        GetPortfoliosInteractor $portfoliosInteractor
    ): ApiResponse {
        $portfoliosResponse = $portfoliosInteractor->execute(
            new GetPortfoliosRequest([
                'userId' => $request->userId(),
                'page' => $request->page(),
                'perPage' => $request->perPage(),
                'sort' => $request->sort(),
                'direction' => $request->direction(),
            ])
        );

        return ApiResponse::success(
            new PortfolioCollectionResource($portfoliosResponse->portfolios),
            [
                'total' => $portfoliosResponse->total,
                'page' => $portfoliosResponse->page,
                'per_page' => $portfoliosResponse->perPage,
                'last_page' => $portfoliosResponse->lastPage,
            ]
        );
    }
}