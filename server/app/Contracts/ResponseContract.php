<?php

declare(strict_types=1);

namespace App\Contracts;

interface ResponseContract
{
    public function toArray($request): array;
}
