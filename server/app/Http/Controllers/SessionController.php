<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Users\Interactors\Sessions\GetUserActiveSessionsInteractor;
use App\Domain\Users\Interactors\Sessions\GetUserActiveSessionsRequest;
use App\Domain\Users\Interactors\Sessions\TerminateSessionInteractor;
use App\Domain\Users\Interactors\Sessions\TerminateSessionRequest;
use App\Domain\Users\Interactors\Sessions\UpdateSessionLastUsedInteractor;
use App\Domain\Users\Interactors\Sessions\UpdateSessionLastUsedRequest;
use App\Domain\Users\Services\TokenService;
use App\Http\ApiResponse;
use App\Http\Exceptions\InvalidSessionId;
use App\Http\Requests\Sessions\GetAccessTokenApiRequest;
use App\Http\Requests\Sessions\GetSessionsApiRequest;
use App\Http\Requests\Sessions\TerminateSessionApiRequest;
use App\Http\Resources\Auth\AccessTokenResource;
use App\Http\Resources\Sessions\SessionCollectionResource;
use App\Http\Resources\Sessions\TerminateSessionResource;
use App\Http\Responses\AccessTokenResponse;

final class SessionController
{
    public function getSessions(
        GetSessionsApiRequest $request,
        GetUserActiveSessionsInteractor $getUserActiveSessionsInteractor
    ): ApiResponse {
        $sessionsResponse = $getUserActiveSessionsInteractor->execute(
            new GetUserActiveSessionsRequest([
                'id' => $request->userId(),
                'page' => $request->page(),
                'perPage' => $request->perPage(),
                'sort' => $request->sort(),
                'direction' => $request->direction(),
            ])
        );

        return ApiResponse::success(
            new SessionCollectionResource($sessionsResponse),
            [
                'total' => $sessionsResponse->total,
                'page' => $sessionsResponse->page,
                'per_page' => $sessionsResponse->perPage,
                'last_page' => $sessionsResponse->lastPage,
            ]
        );
    }

    public function getAccessToken(
        GetAccessTokenApiRequest $request,
        UpdateSessionLastUsedInteractor $updateSessionLastUsedInteractor,
        TokenService $tokenService
    ): ApiResponse {
        $updateSessionLastUsedInteractor->execute(
            new UpdateSessionLastUsedRequest([
                'id' => $request->sessionId(),
            ])
        );

        $accessToken = $tokenService->generateAccessToken($request->sessionId());

        $accessTokenResponse = new AccessTokenResponse([
            'accessToken' => $accessToken,
        ]);

        return ApiResponse::success(new AccessTokenResource($accessTokenResponse));
    }

    public function terminate(
        TerminateSessionApiRequest $request,
        TerminateSessionInteractor $terminateSessionInteractor
    ): ApiResponse {
        $terminateSessionResponse = $terminateSessionInteractor->execute(
            new TerminateSessionRequest([
                'sessionId' => $request->id(),
                'userId' => $request->userId(),
            ])
        );

        return ApiResponse::success(new TerminateSessionResource($terminateSessionResponse));
    }
}
