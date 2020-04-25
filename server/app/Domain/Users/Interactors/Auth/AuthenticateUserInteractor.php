<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Auth;

use App\Domain\Users\Entities\User as UserEntity;
use App\Domain\Users\Exceptions\WrongPassword;
use App\Domain\Users\Services\UserService;
use Illuminate\Support\Facades\Hash;

final class AuthenticateUserInteractor
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function execute(AuthenticateUserRequest $request): AuthenticateUserResponse
    {
        $user = $this->userService->getByUsername($request->username);

        if (!Hash::check($request->password, $user->password)) {
            throw new WrongPassword();
        }

        return new AuthenticateUserResponse([
            'user' => UserEntity::fromModel($user)
        ]);
    }
}
