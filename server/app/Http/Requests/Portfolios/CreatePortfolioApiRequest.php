<?php

declare(strict_types=1);

namespace App\Http\Requests\Portfolios;

use App\Http\Requests\ApiRequest;
use App\Http\Requests\AuthContextTrait;

final class CreatePortfolioApiRequest extends ApiRequest
{
    use AuthContextTrait;

    public function rules(): array
    {
        return [
            'name' => 'required|string'
        ];
    }

    public function name(): string
    {
        return $this->get('name');
    }
}
