<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Users;

use App\Domain\Users\Entities\User as UserEntity;
use App\Domain\Users\Services\UserService;

final class GetUserByIdInteractor
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function execute(GetUserByIdRequest $request): GetUserByIdResponse
    {
        $user = $this->userService->getById($request->id);

        return new GetUserByIdResponse([
            'user' => UserEntity::fromModel($user)
        ]);
    }
}
