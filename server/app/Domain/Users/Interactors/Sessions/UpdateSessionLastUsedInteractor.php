<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Sessions;

use App\Domain\Users\Exceptions\SessionNotFound;
use App\Domain\Users\Models\Session;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class UpdateSessionLastUsedInteractor
{
    public function execute(UpdateSessionLastUsedRequest $request): UpdateSessionLastUsedResponse
    {
        try {
            $session = Session::findOrFail($request->id);
        } catch (ModelNotFoundException $exception) {
            throw new SessionNotFound();
        }

        $session->last_used_at = Carbon::now();
        $session->save();

        return new UpdateSessionLastUsedResponse([
            'id' => $session->id,
        ]);
    }
}
