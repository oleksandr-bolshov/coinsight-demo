<?php

declare(strict_types=1);

namespace App\Domains\Interactors\Sessions;

use App\Domains\Entities\Session as SessionEntity;
use App\Domains\Exceptions\SessionNotFound;
use App\Domains\Models\Session;
use App\Domains\Requests\Sessions\GetActiveSessionByIdRequest;
use App\Domains\Responses\Sessions\GetActiveSessionByIdResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class GetActiveSessionByIdInteractor
{
    public function execute(GetActiveSessionByIdRequest $request): GetActiveSessionByIdResponse
    {
        try {
            $session = Session::where('id', $request->id)->active()->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            throw new SessionNotFound();
        }

        return new GetActiveSessionByIdResponse([
            'session' => SessionEntity::fromModel($session)
        ]);
    }
}
