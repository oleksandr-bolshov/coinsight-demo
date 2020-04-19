<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

final class LoginApiRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required',
            'password' => 'required',
        ];
    }

    public function username(): string
    {
        return $this->get('username');
    }

    public function password(): string
    {
        return $this->get('password');
    }
}
