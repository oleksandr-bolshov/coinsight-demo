<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Domain\Portfolios\Entities\Portfolio as PortfolioEntity;
use App\Domain\Portfolios\Models\Portfolio;
use App\Domain\Portfolios\Services\PortfolioService;
use App\Domain\Users\Services\UserService;

final class CreatePortfolioInteractor
{
    private UserService $userService;
    private PortfolioService $portfolioService;

    public function __construct(UserService $userService, PortfolioService $portfolioService)
    {
        $this->userService = $userService;
        $this->portfolioService = $portfolioService;
    }

    public function execute(CreatePortfolioRequest $request): CreatePortfolioResponse
    {
        $user = $this->userService->getById($request->userId);

        $portfolio = new Portfolio();
        $portfolio->name = $request->name;
        $portfolio->user_id = $user->id;

        $portfolio = $this->portfolioService->store($portfolio);

        return new CreatePortfolioResponse([
            'portfolio' => PortfolioEntity::fromModel($portfolio)
        ]);
    }
}
