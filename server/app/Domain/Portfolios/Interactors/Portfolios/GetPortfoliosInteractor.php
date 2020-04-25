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
        $portfoliosPaginator = $this->portfolioService->paginateByUserId(
            $request->userId, $request->page, $request->perPage, $request->sort, $request->direction
        );

        $portfoliosPaginator->setCollection(
            $portfoliosPaginator->toBase()
                ->map(fn (Portfolio $session) => PortfolioEntity::fromModel($session))
        );

        return new GetPortfoliosResponse([
            'portfolios' => $portfoliosPaginator->getCollection(),
            'total' => $portfoliosPaginator->total(),
            'page' => $portfoliosPaginator->currentPage(),
            'perPage' => $portfoliosPaginator->perPage(),
            'lastPage' => $portfoliosPaginator->lastPage(),
        ]);
    }
}
