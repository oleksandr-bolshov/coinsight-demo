<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Domain\Portfolios\Entities\Portfolio as PortfolioEntity;
use App\Domain\Portfolios\Models\Portfolio;

final class GetPortfoliosInteractor
{
    public function execute(GetPortfoliosRequest $request): GetPortfoliosResponse
    {
        $portfolios = Portfolio::whereUserId($request->userId)->get();

        $portfolios = $portfolios->map(
            fn (Portfolio $portfolio) => PortfolioEntity::fromModel($portfolio)
        );

        return new GetPortfoliosResponse([
            'portfolios' => $portfolios,
        ]);
    }
}
