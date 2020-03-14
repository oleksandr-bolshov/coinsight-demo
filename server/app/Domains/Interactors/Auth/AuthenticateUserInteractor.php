<?php

declare(strict_types=1);

namespace App\Domains\Interactors\Auth;

use App\Domains\Entities\User as UserEntity;
use App\Domains\Exceptions\WrongPassword;
use App\Domains\Exceptions\UserNotFound;
use App\Domains\Models\User;
use App\Domains\Requests\Auth\AuthenticateUserRequest;
use App\Domains\Responses\Auth\AuthenticateUserResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

final class AuthenticateUserInteractor
{
    public function execute(AuthenticateUserRequest $request): AuthenticateUserResponse
    {
        try {
            $user = User::where('username', $request->username)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            throw new UserNotFound();
        }

        if (!Hash::check($request->password, $user->password)) {
            throw new WrongPassword();
        }

        return new AuthenticateUserResponse([
            'user' => UserEntity::fromModel($user)
        ]);
    }
}
