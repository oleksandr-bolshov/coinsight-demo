<?php

declare(strict_types=1);

namespace App\Http\Users\Requests;

use App\Http\Common\Requests\ApiRequest;
use App\Http\Common\Requests\AuthContextTrait;

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
