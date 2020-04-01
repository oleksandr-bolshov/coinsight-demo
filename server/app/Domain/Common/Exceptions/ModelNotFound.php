<?php

declare(strict_types=1);

namespace App\Domain\Common\Exceptions;

use App\Support\Contracts\Exception as ExceptionContract;
use App\Support\Traits\ExceptionToArrayTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Throwable;

class ModelNotFound extends ModelNotFoundException implements ExceptionContract
{
    use ExceptionToArrayTrait;

    protected $message = "Model not found.";

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($this->message, $code, $previous);
    }

    public function getStatus(): int
    {
        return Response::HTTP_NOT_FOUND;
    }
}
