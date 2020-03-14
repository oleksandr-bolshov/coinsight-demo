<?php

declare(strict_types=1);

namespace App\Http\Requests\Sessions;

use App\Http\Requests\ApiRequest;
use App\Http\Requests\AuthContextTrait;

final class GetAccessTokenApiRequest extends ApiRequest
{
    use AuthContextTrait;

    public function rules(): array
    {
        return [];
    }
}
