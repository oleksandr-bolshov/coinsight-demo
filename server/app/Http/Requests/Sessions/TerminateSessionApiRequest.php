<?php

declare(strict_types=1);

namespace App\Http\Requests\Sessions;

use App\Http\Requests\ApiRequest;
use App\Http\Requests\AuthContextTrait;

final class TerminateSessionApiRequest extends ApiRequest
{
    use AuthContextTrait;

    public function rules(): array
    {
        return [
            'id' => 'required|integer|min:1',
        ];
    }

    public function id(): int
    {
        return (int) $this->get('id');
    }
}
