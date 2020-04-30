<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Requests;

use App\Http\Common\Requests\ApiRequest;
use App\Http\Common\Requests\AuthContextTrait;

final class UpdatePortfolioByIdApiRequest extends ApiRequest
{
    use AuthContextTrait;

    public function rules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }

    public function name(): string
    {
        return $this->get('name');
    }

    public function id(): int
    {
        return (int) $this->route('id');
    }
}
