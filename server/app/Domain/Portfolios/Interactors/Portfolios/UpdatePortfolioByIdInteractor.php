<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Domain\Portfolios\Entities\Portfolio;
use App\Domain\Portfolios\Services\PortfolioService;

final class UpdatePortfolioByIdInteractor
{
    private PortfolioService $portfolioService;

    public function __construct(PortfolioService $portfolioService)
    {
        $this->portfolioService = $portfolioService;
    }

    public function execute(UpdatePortfolioByIdRequest $request): UpdatePortfolioByIdResponse
    {
        $portfolio = $this->portfolioService->getByIdAndUserId($request->portfolioId, $request->userId);

        $portfolio->name = $request->name;
        $portfolio->save();

        return new UpdatePortfolioByIdResponse([
            'portfolio' => Portfolio::fromModel($portfolio),
        ]);
    }
}
