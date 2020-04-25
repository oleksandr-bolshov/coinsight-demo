<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Sessions;

use App\Domain\Users\Exceptions\SessionNotFound;
use App\Domain\Users\Models\Session;
use App\Domain\Users\Services\TokenService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class GetAccessTokenInteractor
{
    private TokenService $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function execute(GetAccessTokenRequest $request): GetAccessTokenResponse
    {
        try {
            $session = Session::findOrFail($request->id);
        } catch (ModelNotFoundException $exception) {
            throw new SessionNotFound();
        }

        $session->last_used_at = Carbon::now();
        $session->save();

        $accessToken = $this->tokenService->generateAccessToken($session->id);

        return new GetAccessTokenResponse([
            'accessToken' => $accessToken,
        ]);
    }
}
