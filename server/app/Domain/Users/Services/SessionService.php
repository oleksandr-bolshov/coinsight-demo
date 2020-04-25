<?php

declare(strict_types=1);

namespace App\Domain\Users\Services;

use App\Domain\Users\Exceptions\SessionNotFound;
use App\Domain\Users\Models\Session;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class SessionService
{
    public function getById(int $id): Session
    {
        try {
            return Session::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new SessionNotFound();
        }
    }

    public function getActiveByIdAndUserId(int $sessionId, int $userId): Session
    {
        try {
            return Session::whereId($sessionId)
                ->whereUserId($userId)
                ->active()
                ->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            throw new SessionNotFound();
        }
    }

    public function getActiveById(int $id): Session
    {
        try {
            return Session::whereId($id)->active()->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            throw new SessionNotFound();
        }
    }

    public function paginateActiveByUserId(
        int $userId,
        int $page,
        int $perPage,
        string $sort,
        string $direction
    ): LengthAwarePaginator {
        return Session::orderBy($sort, $direction)
            ->where('user_id', $userId)
            ->active()
            ->paginate($perPage, ['*'], null, $page);
    }

    public function store(Session $session): Session
    {
        $session->created_at = now();
        $session->last_used_at = now();

        $session->save();
        return $session->fresh();
    }
}
