<?php

declare(strict_types=1);

namespace App\Http\Exceptions;

use App\Support\Contracts\ExceptionContract;
use App\Support\Traits\ExceptionToArrayTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

final class RequestValidation extends ValidationException implements ExceptionContract
{
    use ExceptionToArrayTrait;

    public $message;

    public function __construct(Validator $validator, ?Response $response = null, string $errorBag = 'default')
    {
        $this->message = $this->validator->errors();
        parent::__construct($validator, $response, $errorBag);
    }

    public function getStatus(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
