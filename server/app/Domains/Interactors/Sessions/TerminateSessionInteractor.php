<?php

declare(strict_types=1);

namespace App\Domains\Interactors\Sessions;

use App\Domains\Exceptions\SessionNotFound;
use App\Domains\Models\Session;
use App\Domains\Requests\Sessions\TerminateSessionRequest;
use App\Domains\Responses\Sessions\TerminateSessionResponse;
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
