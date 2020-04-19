<?php

declare(strict_types=1);

namespace App\Http\Requests;

final class DefaultRequest extends ApiRequest
{
    use AuthContextTrait;
}
