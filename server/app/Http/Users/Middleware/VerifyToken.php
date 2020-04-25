<?php

declare(strict_types=1);

namespace App\Http\Users\Middleware;

use App\Domain\Users\Interactors\Sessions\GetActiveSessionByIdInteractor;
use App\Domain\Users\Interactors\Sessions\GetActiveSessionByIdRequest;
use App\Domain\Users\Services\TokenService;
use App\Http\Users\Exceptions\TokenRequired;
use Closure;
use Illuminate\Http\Request;

final class VerifyToken
{
    const ACCESS_TOKEN_TYPE = 'access';
    const REFRESH_TOKEN_TYPE = 'refresh';

    private TokenService $tokenService;
    private GetActiveSessionByIdInteractor $activeSessionByIdInteractor;

    public function __construct(
        TokenService $tokenService,
        GetActiveSessionByIdInteractor $activeSessionByIdInteractor
    ) {
        $this->tokenService = $tokenService;
        $this->activeSessionByIdInteractor = $activeSessionByIdInteractor;
    }

    public function handle(Request $request, Closure $next, $tokenType)
    {
        $header = (string) $request->header('Authorization');

        if (count(explode(" ", $header)) === 1) {
            throw new TokenRequired();
        }

        $token = explode(" ", $header)[1];

        if ($tokenType === self::ACCESS_TOKEN_TYPE) {
            $sessionId = $this->tokenService->getSessionIdFromAccessToken($token);
        } else {
            $sessionId = $this->tokenService->getSessionIdFromRefreshToken($token);
        }

        $session = $this->activeSessionByIdInteractor
            ->execute(
                new GetActiveSessionByIdRequest([
                    'id' => $sessionId,
                ])
            )
            ->session;

        $request->merge([
            'session_id' => $session->id,
            'user_id' => $session->userId,
        ]);

        return $next($request);
    }
}
