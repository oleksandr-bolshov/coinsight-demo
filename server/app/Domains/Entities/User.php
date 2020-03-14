<?php

declare(strict_types=1);

namespace App\Domains\Entities;

use App\Domains\Models\User as UserModel;
use Spatie\DataTransferObject\DataTransferObject;

final class User extends DataTransferObject
{
    public int $id;
    public string $username;
    public string $email;

    public static function fromModel(UserModel $user): self
    {
        return new self([
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
        ]);
    }
}
