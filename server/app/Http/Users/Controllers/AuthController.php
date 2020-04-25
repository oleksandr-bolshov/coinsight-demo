<?php

declare(strict_types=1);

namespace App\Http\Users\Controllers;

use App\Domain\Users\Interactors\Auth\LoginInteractor;
use App\Domain\Users\Interactors\Auth\LoginRequest;
use App\Domain\Users\Interactors\Auth\RegisterInteractor;
use App\Domain\Users\Interactors\Auth\RegisterRequest;
use App\Domain\Users\Interactors\Users\GetUserByIdInteractor;
use App\Domain\Users\Interactors\Users\GetUserByIdRequest;
use App\Http\Common\ApiResponse;
use App\Http\Common\Requests\DefaultRequest;
use App\Http\Users\Requests\LoginApiRequest;
use App\Http\Users\Requests\RegisterApiRequest;
use App\Http\Users\Resources\LoginResource;
use App\Http\Users\Resources\UserResource;

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
        LoginInteractor $loginInteractor
    ): ApiResponse {
        $loginResponse = $loginInteractor->execute(
            new LoginRequest([
                'username' => $request->username(),
                'password' => $request->password(),
            ])
        );

        return ApiResponse::success(new LoginResource($loginResponse));
    }

    public function me(
        DefaultRequest $request,
        GetUserByIdInteractor $getUserByIdInteractor
    ): ApiResponse {
        $user = $getUserByIdInteractor
            ->execute(
                new GetUserByIdRequest([
                    'id' => $request->userId(),
                ])
            )
            ->user;

        return ApiResponse::success(new UserResource($user));
    }
}
