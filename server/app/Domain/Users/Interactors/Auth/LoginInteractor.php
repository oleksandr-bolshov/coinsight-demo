<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Auth;

use App\Domain\Users\Interactors\Sessions\CreateSessionInteractor;
use App\Domain\Users\Interactors\Sessions\CreateSessionRequest;
use App\Domain\Users\Services\TokenService;

final class LoginInteractor
{
    private AuthenticateUserInteractor $authenticateUserInteractor;
    private CreateSessionInteractor $createSessionInteractor;
    private TokenService $tokenService;

    public function __construct(
        AuthenticateUserInteractor $authenticateUserInteractor,
        CreateSessionInteractor $createSessionInteractor,
        TokenService $tokenService
    ) {
        $this->authenticateUserInteractor = $authenticateUserInteractor;
        $this->createSessionInteractor = $createSessionInteractor;
        $this->tokenService = $tokenService;
    }

    public function execute(LoginRequest $request): LoginResponse
    {
        $user = $this->authenticateUserInteractor
            ->execute(
                new AuthenticateUserRequest([
                    'username' => $request->username,
                    'password' => $request->password,
                ])
            )
            ->user;

        $session = $this->createSessionInteractor
            ->execute(
                new CreateSessionRequest([
                    'userId' => $user->id
                ])
            )
            ->session;

        return new LoginResponse([
            'accessToken' => $this->tokenService->generateAccessToken($session->id),
            'refreshToken' => $this->tokenService->generateRefreshToken($session->id),
        ]);
    }
}
