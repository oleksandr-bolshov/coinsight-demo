<?php

declare(strict_types=1);

namespace App\Domains\Interactors\Sessions;

use App\Domains\Entities\Session as SessionEntity;
use App\Domains\Exceptions\UserNotFound;
use App\Domains\Models\Session;
use App\Domains\Models\User;
use App\Domains\Requests\Sessions\CreateSessionRequest;
use App\Domains\Responses\Sessions\CreateSessionResponse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class CreateSessionInteractor
{
    public function execute(CreateSessionRequest $request): CreateSessionResponse
    {
        try {
            $user = User::findOrFail($request->userId);
        } catch (ModelNotFoundException $exception) {
            throw new UserNotFound();
        }

        $session = new Session();
        $session->user_id = $user->id;

        $now = Carbon::now();
        $session->created_at = $now;
        $session->last_used_at = $now;

        $session->save();
        $session->refresh();

        return new CreateSessionResponse([
            'session' => SessionEntity::fromModel($session),
        ]);
    }
}
