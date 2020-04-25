<?php

declare(strict_types=1);

namespace App\Http\Markets\Requests;

use App\Http\Common\Requests\ApiRequest;

final class GetCoinProfileApiRequest extends ApiRequest
{
    public function id(): int
    {
        return (int) $this->route('id');
    }
}
