<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\Interactors\Sessions\UpdateSessionLastUsedInteractor;
use App\Domains\Requests\Sessions\UpdateSessionLastUsedRequest;
use App\Http\ApiResponse;
use App\Http\Requests\Sessions\GetAccessTokenApiRequest;
use App\Http\Resources\AccessTokenResource;
use App\Http\Responses\AccessTokenResponse;
use App\Http\Services\TokenService;

final class SessionController
{
    public function getAccessToken(GetAccessTokenApiRequest $request): ApiResponse
    {
        (new UpdateSessionLastUsedInteractor())->execute(
            new UpdateSessionLastUsedRequest([
                'id' => $request->sessionId(),
            ])
        );

        $accessToken = (new TokenService())->generateAccessToken($request->sessionId());

        $accessTokenResponse = new AccessTokenResponse([
            'accessToken' => $accessToken,
        ]);

        return ApiResponse::success(new AccessTokenResource($accessTokenResponse));
    }
}
