<?php

namespace App\Exceptions;

use App\Lib\HttpStatus;
use App\Lib\JSON;

class BaseException extends \Exception
{
    protected $message;
    protected $serverStatusCode;
    protected $exceptionType;

    public function __construct(string $message, int $statusCode)
    {
        $this->message = $message;
        $this->serverStatusCode = $statusCode;
        HttpStatus::changeHttpStatus($this->serverStatusCode);
    }

    public function getExceptionResponse()
    {
        return JSON::response(
            array(
                "error" => [
                    "type" => $this->exceptionType,
                    "message" => $this->message,
                ]
            )
        );
    }
}