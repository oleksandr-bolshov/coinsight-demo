<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Spatie\DataTransferObject\DataTransferObject;

final class LoginResponse extends DataTransferObject
{
    public string $accessToken;
    public string $refreshToken;
}
