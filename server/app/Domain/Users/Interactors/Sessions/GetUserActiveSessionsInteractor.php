<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Sessions;

use App\Domain\Users\Entities\Session as SessionEntity;
use App\Domain\Users\Models\Session;
use App\Domain\Users\Services\SessionService;
use App\Domain\Users\Services\UserService;

final class GetUserActiveSessionsInteractor
{
    private SessionService $sessionService;

    public function __construct(SessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    public function execute(GetUserActiveSessionsRequest $request): GetUserActiveSessionsResponse
    {
        $sessionsPaginator = $this->sessionService->paginateActiveByUserId(
            $request->id, $request->page, $request->perPage, $request->sort, $request->direction
        );

        $sessionsPaginator->setCollection(
            $sessionsPaginator->toBase()
                ->map(fn(Session $session) => SessionEntity::fromModel($session))
        );

        return new GetUserActiveSessionsResponse([
            'sessions' => $sessionsPaginator->getCollection(),
            'total' => $sessionsPaginator->total(),
            'page' => $sessionsPaginator->currentPage(),
            'perPage' => $sessionsPaginator->perPage(),
            'lastPage' => $sessionsPaginator->lastPage(),
        ]);
    }
}
