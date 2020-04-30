<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Controllers;

use App\Domain\Portfolios\Interactors\Portfolios\CreatePortfolioInteractor;
use App\Domain\Portfolios\Interactors\Portfolios\CreatePortfolioRequest;
use App\Domain\Portfolios\Interactors\Portfolios\DeletePortfolioByIdInteractor;
use App\Domain\Portfolios\Interactors\Portfolios\DeletePortfolioByIdRequest;
use App\Domain\Portfolios\Interactors\Portfolios\GetPortfolioReportByIdInteractor;
use App\Domain\Portfolios\Interactors\Portfolios\GetPortfolioReportByIdRequest;
use App\Domain\Portfolios\Interactors\Portfolios\GetPortfoliosInteractor;
use App\Domain\Portfolios\Interactors\Portfolios\GetPortfoliosRequest;
use App\Domain\Portfolios\Interactors\Portfolios\UpdatePortfolioByIdInteractor;
use App\Domain\Portfolios\Interactors\Portfolios\UpdatePortfolioByIdRequest;
use App\Http\Common\Resources\MutationResource;
use App\Http\Portfolios\Requests\CreatePortfolioApiRequest;
use App\Http\Portfolios\Requests\DeletePortfolioByIdApiRequest;
use App\Http\Portfolios\Requests\GetPortfolioReportByIdApiRequest;
use App\Http\Portfolios\Requests\GetPortfoliosApiRequest;
use App\Http\Portfolios\Requests\UpdatePortfolioByIdApiRequest;
use App\Http\Portfolios\Resources\PortfolioCollectionResource;
use App\Http\Portfolios\Resources\PortfolioReportResource;
use App\Http\Portfolios\Resources\PortfolioResource;
use App\Http\Common\ApiResponse;

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

    public function getPortfolioReportById(
        GetPortfolioReportByIdApiRequest $request,
        GetPortfolioReportByIdInteractor $portfolioReportByIdInteractor
    ): ApiResponse {
        $report = $portfolioReportByIdInteractor
            ->execute(new GetPortfolioReportByIdRequest([
                'userId' => $request->userId(),
                'portfolioId' => $request->id(),
            ]))
            ->report;

        return ApiResponse::success(new PortfolioReportResource($report));
    }

    public function updatePortfolioById(
        UpdatePortfolioByIdApiRequest $request,
        UpdatePortfolioByIdInteractor $updatePortfolioByIdInteractor
    ): ApiResponse {
        $portfolio = $updatePortfolioByIdInteractor
            ->execute(new UpdatePortfolioByIdRequest([
                'portfolioId' => $request->id(),
                'userId' => $request->userId(),
                'name' => $request->name(),
            ]))
            ->portfolio;

        return ApiResponse::success(new PortfolioResource($portfolio));
    }

    public function deletePortfolioById(
        DeletePortfolioByIdApiRequest $request,
        DeletePortfolioByIdInteractor $deletePortfolioByIdInteractor
    ): ApiResponse {
        $deletedPortfolioResponse = $deletePortfolioByIdInteractor->execute(
            new DeletePortfolioByIdRequest([
                'portfolioId' => $request->id(),
                'userId' => $request->userId(),
            ])
        );

        return ApiResponse::success(new MutationResource($deletedPortfolioResponse));
    }
}
