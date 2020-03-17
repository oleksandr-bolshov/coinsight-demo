<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Auth;

use App\Domain\Users\Models\User;
use Illuminate\Support\Facades\Hash;

final class RegisterInteractor
{
    public function execute(RegisterRequest $request): RegisterResponse
    {
        $user = new User();

        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        $user->refresh();

        return new RegisterResponse([
           'id' => $user->id,
        ]);
    }
}
