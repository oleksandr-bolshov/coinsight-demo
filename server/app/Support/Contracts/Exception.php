<?php

declare(strict_types=1);

namespace App\Support\Contracts;

interface Exception
{
    public function getStatus(): int;
    public function toArray(): array;
}
