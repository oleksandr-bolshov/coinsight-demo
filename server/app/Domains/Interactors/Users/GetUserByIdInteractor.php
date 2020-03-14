<?php

declare(strict_types=1);

namespace App\Domains\Interactors\Users;

use App\Domains\Entities\User as UserEntity;
use App\Domains\Models\User;
use App\Domains\Requests\Users\GetUserByIdRequest;
use App\Domains\Responses\Users\GetUserByIdResponse;

final class GetUserByIdInteractor
{
    public function execute(GetUserByIdRequest $request): GetUserByIdResponse
    {
        $user = User::findOrFail($request->id);

        return new GetUserByIdResponse([
            'user' => UserEntity::fromModel($user)
        ]);
    }
}
