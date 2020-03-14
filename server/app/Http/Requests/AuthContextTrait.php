<?php

declare(strict_types=1);

namespace App\Http\Requests;

trait AuthContextTrait
{
    public function userId(): int
    {
        return (int) $this->get('user_id');
    }

    public function sessionId(): int
    {
        return (int) $this->get('session_id');
    }
}
