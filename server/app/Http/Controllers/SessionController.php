<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Users\Interactors\Sessions\GetUserActiveSessionsInteractor;
use App\Domain\Users\Interactors\Sessions\GetUserActiveSessionsRequest;
use App\Domain\Users\Interactors\Sessions\TerminateSessionInteractor;
use App\Domain\Users\Interactors\Sessions\TerminateSessionRequest;
use App\Domain\Users\Interactors\Sessions\GetAccessTokenInteractor;
use App\Domain\Users\Interactors\Sessions\GetAccessTokenRequest;
use App\Http\ApiResponse;
use App\Http\Requests\DefaultRequest;
use App\Http\Requests\Sessions\GetSessionsApiRequest;
use App\Http\Requests\Sessions\TerminateSessionApiRequest;
use App\Http\Resources\Auth\AccessTokenResource;
use App\Http\Resources\Sessions\SessionCollectionResource;
use App\Http\Resources\Sessions\TerminateSessionResource;

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
        DefaultRequest $request,
        GetAccessTokenInteractor $accessTokenInteractor
    ): ApiResponse {
        $accessTokenResponse = $accessTokenInteractor->execute(
            new GetAccessTokenRequest([
                'id' => $request->sessionId(),
            ])
        );

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
