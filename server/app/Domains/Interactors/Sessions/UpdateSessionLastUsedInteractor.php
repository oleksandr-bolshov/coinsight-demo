<?php

declare(strict_types=1);

namespace App\Domains\Interactors\Sessions;

use App\Domains\Exceptions\SessionNotFound;
use App\Domains\Models\Session;
use App\Domains\Requests\Sessions\UpdateSessionLastUsedRequest;
use App\Domains\Responses\Sessions\UpdateSessionLastUsedResponse;
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
