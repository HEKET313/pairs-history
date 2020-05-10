<?php

namespace App\Exception;

use Throwable;

class ProcessedErrorException extends \Exception
{
    private string $errorCode = '';
    private array $data;

    public function __construct(string $message, string $errorCode, int $code, array $data = [])
    {
        $this->errorCode = $errorCode;
        $this->data = $data;
        parent::__construct($message, $code);
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
