<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;

class BadRequestException extends ProcessedErrorException
{
    public function __construct(string $message, string $errorCode, array $data = [])
    {
        parent::__construct($message, $errorCode, JsonResponse::HTTP_BAD_REQUEST, $data);
    }
}
