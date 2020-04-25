<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Sessions;

use Spatie\DataTransferObject\DataTransferObject;

final class GetAccessTokenResponse extends DataTransferObject
{
    public string $accessToken;
}
