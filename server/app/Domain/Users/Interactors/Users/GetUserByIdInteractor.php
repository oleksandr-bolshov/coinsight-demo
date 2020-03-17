<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Users;

use App\Domain\Users\Entities\User as UserEntity;
use App\Domain\Users\Models\User;

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
