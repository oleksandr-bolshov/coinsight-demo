<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Domain\Portfolios\Services\PortfolioService;

final class DeletePortfolioByIdInteractor
{
    private PortfolioService $portfolioService;

    public function __construct(PortfolioService $portfolioService)
    {
        $this->portfolioService = $portfolioService;
    }

    public function execute(DeletePortfolioByIdRequest $request): DeletePortfolioByIdResponse
    {
        $portfolio = $this->portfolioService->getByIdAndUserId($request->portfolioId, $request->userId);

        $portfolio->delete();

        return new DeletePortfolioByIdResponse([
            'id' => $portfolio->id,
        ]);
    }
}
