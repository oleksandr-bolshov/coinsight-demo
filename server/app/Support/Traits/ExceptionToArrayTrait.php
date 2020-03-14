<?php

declare(strict_types=1);

namespace App\Support\Traits;

trait ExceptionToArrayTrait
{
    public function toArray(): array
    {
        return [
            'type' => class_basename(get_class($this)),
            'message' => $this->message,
        ];
    }
}
