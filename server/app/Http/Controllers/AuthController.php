<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Users\Interactors\Auth\AuthenticateUserInteractor;
use App\Domain\Users\Interactors\Auth\AuthenticateUserRequest;
use App\Domain\Users\Interactors\Auth\RegisterInteractor;
use App\Domain\Users\Interactors\Auth\RegisterRequest;
use App\Domain\Users\Interactors\Sessions\CreateSessionInteractor;
use App\Domain\Users\Interactors\Sessions\CreateSessionRequest;
use App\Domain\Users\Interactors\Users\GetUserByIdInteractor;
use App\Domain\Users\Interactors\Users\GetUserByIdRequest;
use App\Http\ApiResponse;
use App\Http\Requests\Auth\GetCurrentUserApiRequest;
use App\Http\Requests\Auth\LoginApiRequest;
use App\Http\Requests\Auth\RegisterApiRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\UserResource;
use App\Http\Responses\LoginResponse;
use App\Http\Services\TokenService;

final class AuthController
{
    public function register(
        RegisterApiRequest $request,
        RegisterInteractor $registerInteractor
    ): ApiResponse {
        $registerInteractor->execute(
            new RegisterRequest([
                'email' => $request->email(),
                'username' => $request->username(),
                'password' => $request->password(),
            ])
        );

        return ApiResponse::empty();
    }

    public function login(
        LoginApiRequest $request,
        AuthenticateUserInteractor $authenticateUserInteractor,
        CreateSessionInteractor $createSessionInteractor,
        TokenService $tokenService
    ): ApiResponse {
        $user = $authenticateUserInteractor->execute(
            new AuthenticateUserRequest([
                'username' => $request->username(),
                'password' => $request->password(),
            ])
        )->user;

        $session = $createSessionInteractor->execute(
            new CreateSessionRequest([
                'userId' => $user->id
            ])
        )->session;

        $loginResponse = new LoginResponse([
            'accessToken' => $tokenService->generateAccessToken($session->id),
            'refreshToken' => $tokenService->generateRefreshToken($session->id),
        ]);

        return ApiResponse::success(new LoginResource($loginResponse));
    }

    public function me(
        GetCurrentUserApiRequest $request,
        GetUserByIdInteractor $getUserByIdInteractor
    ): ApiResponse {
        $user = $getUserByIdInteractor->execute(
            new GetUserByIdRequest([
                'id' => $request->userId(),
            ])
        )->user;

        return ApiResponse::success(new UserResource($user));
    }
}
