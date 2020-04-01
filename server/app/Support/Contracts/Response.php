<?php

declare(strict_types=1);

namespace App\Support\Contracts;

interface Response
{
    public function toArray($request): array;
}
