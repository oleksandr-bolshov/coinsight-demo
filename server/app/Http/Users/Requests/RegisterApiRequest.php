<?php

declare(strict_types=1);

namespace App\Http\Users\Requests;

use App\Http\Common\Requests\ApiRequest;

final class RegisterApiRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required|alpha_dash|between:3,50|unique:users',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8',
        ];
    }

    public function email(): string
    {
        return $this->get('email');
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
