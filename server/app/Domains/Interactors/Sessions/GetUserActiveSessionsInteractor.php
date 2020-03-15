<?php

declare(strict_types=1);

namespace App\Domains\Interactors\Sessions;

use App\Domains\Entities\Session as SessionEntity;
use App\Domains\Exceptions\UserNotFound;
use App\Domains\Models\Session;
use App\Domains\Models\User;
use App\Domains\Requests\Sessions\GetUserActiveSessionsRequest;
use App\Domains\Responses\Sessions\GetUserActiveSessionsResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class GetUserActiveSessionsInteractor
{
    public function execute(GetUserActiveSessionsRequest $request): GetUserActiveSessionsResponse
    {
        try {
            $user = User::findOrFail($request->id);
        } catch (ModelNotFoundException $exception) {
            throw new UserNotFound();
        }

        $sessionsPaginator = Session::orderBy($request->sort, $request->direction)
            ->where('user_id', $user->id)
            ->active()
            ->paginate($request->perPage, ['*'], null, $request->page);

        $sessionsPaginator->setCollection(
            $sessionsPaginator->toBase()
                ->map(fn(Session $session) => SessionEntity::fromModel($session))
        );

        return new GetUserActiveSessionsResponse([
            'sessions' => $sessionsPaginator->getCollection(),
            'total' => $sessionsPaginator->total(),
            'page' => $sessionsPaginator->currentPage(),
            'perPage' => $sessionsPaginator->perPage(),
            'lastPage' => $sessionsPaginator->lastPage(),
        ]);
    }
}
