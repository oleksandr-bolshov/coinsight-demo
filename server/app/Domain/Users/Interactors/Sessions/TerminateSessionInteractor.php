<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Sessions;

use App\Domain\Users\Exceptions\SessionNotFound;
use App\Domain\Users\Models\Session;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class TerminateSessionInteractor
{
    public function execute(TerminateSessionRequest $request): TerminateSessionResponse
    {
        try {
            $session = Session::findOrFail($request->id);
        } catch (ModelNotFoundException $exception) {
            throw new SessionNotFound();
        }

        $session->terminated_at = Carbon::now();
        $session->save();

        return new TerminateSessionResponse([
            'id' => $request->id
        ]);
    }
}
