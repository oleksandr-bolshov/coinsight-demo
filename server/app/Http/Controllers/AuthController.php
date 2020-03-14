<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\Interactors\Auth\AuthenticateUserInteractor;
use App\Domains\Interactors\Auth\RegisterInteractor;
use App\Domains\Interactors\Sessions\CreateSessionInteractor;
use App\Domains\Requests\Auth\AuthenticateUserRequest;
use App\Domains\Requests\Auth\RegisterRequest;
use App\Domains\Requests\Sessions\CreateSessionRequest;
use App\Http\ApiResponse;
use App\Http\Requests\Auth\LoginApiRequest;
use App\Http\Requests\Auth\RegisterApiRequest;
use App\Http\Resources\LoginResource;
use App\Http\Responses\LoginResponse;
use App\Http\Services\TokenService;

final class AuthController
{
    private TokenService $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function register(RegisterApiRequest $request): ApiResponse
    {
        (new RegisterInteractor())->execute(
            new RegisterRequest([
                'email' => $request->email(),
                'username' => $request->username(),
                'password' => $request->password(),
            ])
        );

        return ApiResponse::empty();
    }

    public function login(LoginApiRequest $request): ApiResponse
    {
        $user = (new AuthenticateUserInteractor)->execute(
            new AuthenticateUserRequest([
                'username' => $request->username(),
                'password' => $request->password(),
            ])
        )->user;

        $session = (new CreateSessionInteractor)->execute(
            new CreateSessionRequest([
                'userId' => $user->id
            ])
        )->session;

        $loginResponse = new LoginResponse([
            'accessToken' => $this->tokenService->generateAccessToken($session->id),
            'refreshToken' => $this->tokenService->generateRefreshToken($session->id),
        ]);

        return ApiResponse::success(new LoginResource($loginResponse));
    }
}
