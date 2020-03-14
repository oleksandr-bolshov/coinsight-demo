<?php

declare(strict_types=1);

namespace App\Domains\Interactors\Auth;

use App\Domains\Models\User;
use App\Domains\Requests\Auth\RegisterRequest;
use App\Domains\Responses\Auth\RegisterResponse;
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
