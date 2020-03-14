<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;
use App\Http\Requests\AuthContextTrait;

final class GetCurrentUserApiRequest extends ApiRequest
{
    use AuthContextTrait;

    public function rules(): array
    {
        return [];
    }
}
