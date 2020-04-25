<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Sessions;

use App\Domain\Users\Services\SessionService;
use Carbon\Carbon;

final class TerminateSessionInteractor
{
    private SessionService $sessionService;

    public function __construct(SessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    public function execute(TerminateSessionRequest $request): TerminateSessionResponse
    {
        $session = $this->sessionService->getActiveByIdAndUserId($request->sessionId, $request->userId);

        $session->terminated_at = Carbon::now();
        $session->save();

        return new TerminateSessionResponse([
            'id' => $session->id
        ]);
    }
}
