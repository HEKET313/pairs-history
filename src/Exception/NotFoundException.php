<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class NotFoundException extends ProcessedErrorException
{
    public const CODE_NOT_FOUND = 'NOT_FOUND';

    public function __construct(string $message, string $errorCode = self::CODE_NOT_FOUND)
    {
        parent::__construct($message, $errorCode, JsonResponse::HTTP_NOT_FOUND);
    }
}
