<?php

declare(strict_types=1);

namespace App\Domain\Markets\Services;

use App\Domain\Markets\Exceptions\CoinNotFound;
use App\Domain\Markets\Models\Coin;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class CoinService
{
    public function getById(int $id, array $withRelations = []): Coin
    {
        try {
            return Coin::with($withRelations)->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new CoinNotFound();
        }
    }

    public function getCollectionByNames(array $names): Collection
    {
        return Coin::whereIn('name', $names)->get();
    }
}
