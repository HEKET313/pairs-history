<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class InternalErrorException extends ProcessedErrorException
{
    public function __construct(string $errorCode, Throwable $previous = null)
    {
        parent::__construct($previous->getMessage(), $errorCode, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
