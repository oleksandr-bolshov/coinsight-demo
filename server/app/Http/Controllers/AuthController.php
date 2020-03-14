<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\Interactors\Auth\RegisterInteractor;
use App\Domains\Requests\Auth\RegisterRequest;
use App\Http\ApiResponse;
use App\Http\Requests\Auth\RegisterApiRequest;

final class AuthController
{
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
}
