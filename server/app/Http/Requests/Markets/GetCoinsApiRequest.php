<?php

declare(strict_types=1);

namespace App\Http\Requests\Markets;

use App\Http\Requests\ApiRequest;
use App\Http\Requests\AuthContextTrait;

final class GetCoinsApiRequest extends ApiRequest
{
    use AuthContextTrait;

    public function rules(): array
    {
        return [
            'page' => 'required|integer|min:1',
            'per_page' => 'required|integer|min:1|max:100',
        ];
    }

    public function page(): ?int
    {
        return (int) $this->get('page');
    }

    public function perPage(): ?int
    {
        return (int) $this->get('per_page');
    }
}
