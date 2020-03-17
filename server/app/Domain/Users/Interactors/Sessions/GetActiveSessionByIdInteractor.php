<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Sessions;

use App\Domain\Users\Entities\Session as SessionEntity;
use App\Domain\Users\Exceptions\SessionNotFound;
use App\Domain\Users\Models\Session;
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
