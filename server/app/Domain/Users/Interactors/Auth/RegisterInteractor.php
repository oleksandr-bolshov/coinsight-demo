<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Auth;

use App\Domain\Users\Models\User;
use App\Domain\Users\Services\UserService;
use Illuminate\Support\Facades\Hash;

final class RegisterInteractor
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function execute(RegisterRequest $request): RegisterResponse
    {
        $user = new User();

        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user = $this->userService->store($user);

        return new RegisterResponse([
           'id' => $user->id,
        ]);
    }
}
