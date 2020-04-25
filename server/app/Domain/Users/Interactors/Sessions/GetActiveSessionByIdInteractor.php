<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Sessions;

use App\Domain\Users\Entities\Session as SessionEntity;
use App\Domain\Users\Services\SessionService;

final class GetActiveSessionByIdInteractor
{
    private SessionService $sessionService;

    public function __construct(SessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    public function execute(GetActiveSessionByIdRequest $request): GetActiveSessionByIdResponse
    {
        $session = $this->sessionService->getActiveById($request->id);

        return new GetActiveSessionByIdResponse([
            'session' => SessionEntity::fromModel($session)
        ]);
    }
}
