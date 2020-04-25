<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Services;

use App\Domain\Common\Exceptions\ModelNotFound;
use App\Domain\Portfolios\Exceptions\PortfolioNotFound;
use App\Domain\Portfolios\Models\Portfolio;
use Illuminate\Database\Eloquent\Collection;

final class PortfolioService
{
    public function getByIdAndUserId(int $portfolioId, int $userId): Portfolio
    {
        try {
            return Portfolio::whereId($portfolioId)
                ->whereUserId($userId)
                ->firstOrFail();
        } catch (ModelNotFound $exception) {
            throw new PortfolioNotFound();
        }
    }

    public function getCollectionByUserId(int $id): Collection
    {
        return Portfolio::whereUserId($id)->get();
    }

    public function store(Portfolio $portfolio): Portfolio
    {
        $portfolio->save();
        return $portfolio->fresh();
    }
}
