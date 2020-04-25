<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Domain\Portfolios\Entities\Portfolio as PortfolioEntity;
use App\Domain\Portfolios\Models\Portfolio;
use App\Domain\Portfolios\Services\PortfolioService;

final class GetPortfoliosInteractor
{
    private PortfolioService $portfolioService;

    public function __construct(PortfolioService $portfolioService)
    {
        $this->portfolioService = $portfolioService;
    }

    public function execute(GetPortfoliosRequest $request): GetPortfoliosResponse
    {
        $portfolios = $this->portfolioService->getCollectionByUserId($request->userId);

        $portfolios = $portfolios->map(
            fn (Portfolio $portfolio) => PortfolioEntity::fromModel($portfolio)
        );

        return new GetPortfoliosResponse([
            'portfolios' => $portfolios,
        ]);
    }
}
