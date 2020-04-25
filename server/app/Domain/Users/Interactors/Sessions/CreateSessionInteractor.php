<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Sessions;

use App\Domain\Users\Entities\Session as SessionEntity;
use App\Domain\Users\Models\Session;
use App\Domain\Users\Services\SessionService;
use App\Domain\Users\Services\UserService;

final class CreateSessionInteractor
{
    private UserService $userService;
    private SessionService $sessionService;

    public function __construct(UserService $userService, SessionService $sessionService)
    {
        $this->userService = $userService;
        $this->sessionService = $sessionService;
    }

    public function execute(CreateSessionRequest $request): CreateSessionResponse
    {
        $user = $this->userService->getById($request->userId);

        $session = new Session();
        $session->user_id = $user->id;

        $this->sessionService->store($session);

        return new CreateSessionResponse([
            'session' => SessionEntity::fromModel($session),
        ]);
    }
}
