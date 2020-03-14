<?php

declare(strict_types=1);

namespace App\Support\Contracts;

interface ResponseContract
{
    public function toArray($request): array;
}
