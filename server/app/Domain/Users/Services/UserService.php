<?php

declare(strict_types=1);

namespace App\Domain\Users\Services;

use App\Domain\Users\Exceptions\UserNotFound;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class UserService
{
    public function getById(int $id): User
    {
        try {
            return User::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new UserNotFound();
        }
    }

    public function getByUsername(string $username): User
    {
        try {
             return User::where('username', $username)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            throw new UserNotFound();
        }
    }

    public function store(User $user): User
    {
        $user->save();
        return $user->fresh();
    }
}
