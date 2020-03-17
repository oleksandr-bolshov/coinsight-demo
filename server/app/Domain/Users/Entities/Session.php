<?php

declare(strict_types=1);

namespace App\Domain\Users\Entities;

use App\Domain\Users\Models\Session as SessionModel;
use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

final class Session extends DataTransferObject
{
    public int $id;
    public int $userId;
    public Carbon $createdAt;
    public Carbon $lastUsedAt;

    public static function fromModel(SessionModel $session): self
    {
        return new self([
            'id' => $session->id,
            'userId' => $session->user_id,
            'createdAt' => $session->created_at,
            'lastUsedAt' => $session->last_used_at,
        ]);
    }
}
