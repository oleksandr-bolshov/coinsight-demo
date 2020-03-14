<?php

declare(strict_types=1);

namespace App\Support\Contracts;

interface ExceptionContract
{
    public function getStatus(): int;
    public function toArray(): array;
}
