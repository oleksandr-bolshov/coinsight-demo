<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Sessions;

use App\Domain\Users\Entities\Session as SessionEntity;
use App\Domain\Users\Exceptions\UserNotFound;
use App\Domain\Users\Models\Session;
use App\Domain\Users\Models\User;
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
