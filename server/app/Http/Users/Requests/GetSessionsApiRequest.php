<?php

declare(strict_types=1);

namespace App\Http\Users\Requests;

use App\Http\Common\Requests\ApiRequest;
use App\Http\Common\Requests\AuthContextTrait;

final class GetSessionsApiRequest extends ApiRequest
{
    use AuthContextTrait;

    public function rules(): array
    {
        return [
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:50',
            'sort' => 'string',
            'direction' => 'string|in:asc,desc',
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

    public function sort(): ?string
    {
        return $this->get('sort');
    }

    public function direction(): ?string
    {
        return $this->get('direction');
    }
}
