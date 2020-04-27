<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Services;

use App\Domain\Common\Exceptions\ModelNotFound;
use App\Domain\Portfolios\Exceptions\PortfolioNotFound;
use App\Domain\Portfolios\Models\Portfolio;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class PortfolioService
{
    public function getByIdAndUserId(int $portfolioId, int $userId, array $withRelations = []): Portfolio
    {
        try {
            return Portfolio::with($withRelations)
                ->whereId($portfolioId)
                ->whereUserId($userId)
                ->firstOrFail();
        } catch (ModelNotFound $exception) {
            throw new PortfolioNotFound();
        }
    }

    public function paginateByUserId(
        int $userId,
        int $page,
        int $perPage,
        string $sort,
        string $direction
    ): LengthAwarePaginator {
        return Portfolio::orderBy($sort, $direction)
            ->where('user_id', $userId)
            ->paginate($perPage, ['*'], null, $page);
    }

    public function store(Portfolio $portfolio): Portfolio
    {
        $portfolio->save();
        return $portfolio->fresh();
    }
}
