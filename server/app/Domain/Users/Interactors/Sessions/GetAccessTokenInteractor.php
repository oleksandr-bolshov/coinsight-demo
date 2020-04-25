<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Sessions;

use App\Domain\Users\Services\SessionService;
use App\Domain\Users\Services\TokenService;

final class GetAccessTokenInteractor
{
    private SessionService $sessionService;
    private TokenService $tokenService;

    public function __construct(SessionService $sessionService, TokenService $tokenService)
    {
        $this->sessionService = $sessionService;
        $this->tokenService = $tokenService;
    }

    public function execute(GetAccessTokenRequest $request): GetAccessTokenResponse
    {
        $session = $this->sessionService->getById($request->id);

        $session->last_used_at = now();
        $session->save();

        $accessToken = $this->tokenService->generateAccessToken($session->id);

        return new GetAccessTokenResponse([
            'accessToken' => $accessToken,
        ]);
    }
}
