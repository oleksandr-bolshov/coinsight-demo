<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\Interactors\Sessions\GetActiveSessionByIdInteractor;
use App\Domains\Interactors\Sessions\GetUserActiveSessionsInteractor;
use App\Domains\Interactors\Sessions\TerminateSessionInteractor;
use App\Domains\Interactors\Sessions\UpdateSessionLastUsedInteractor;
use App\Domains\Requests\Sessions\GetActiveSessionByIdRequest;
use App\Domains\Requests\Sessions\GetUserActiveSessionsRequest;
use App\Domains\Requests\Sessions\TerminateSessionRequest;
use App\Domains\Requests\Sessions\UpdateSessionLastUsedRequest;
use App\Http\ApiResponse;
use App\Http\Exceptions\InvalidSessionId;
use App\Http\Requests\Sessions\GetAccessTokenApiRequest;
use App\Http\Requests\Sessions\GetSessionsApiRequest;
use App\Http\Requests\Sessions\TerminateSessionApiRequest;
use App\Http\Resources\AccessTokenResource;
use App\Http\Resources\SessionCollectionResource;
use App\Http\Resources\TerminateSessionResource;
use App\Http\Responses\AccessTokenResponse;
use App\Http\Responses\TerminateSessionResponse;
use App\Http\Services\TokenService;

final class SessionController
{
    public function getSessions(GetSessionsApiRequest $request): ApiResponse
    {
        $sessionsResponse = (new GetUserActiveSessionsInteractor())->execute(
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

    public function terminate(TerminateSessionApiRequest $request): ApiResponse
    {
        $session = (new GetActiveSessionByIdInteractor())->execute(
            new GetActiveSessionByIdRequest([
                'id' => $request->id()
            ])
        )->session;

        if ($session->userId === $request->userId()) {
            $sessionId = (new TerminateSessionInteractor())->execute(
                new TerminateSessionRequest([
                    'id' => $request->id(),
                ])
            )->id;

            $terminateSessionResponse = new TerminateSessionResponse([
                'id' => $sessionId,
            ]);

            return ApiResponse::success(new TerminateSessionResource($terminateSessionResponse));
        } else {
            throw new InvalidSessionId();
        }
    }
}
