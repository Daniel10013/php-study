<?php

namespace App\Exceptions;

use App\Core\Exceptions\BaseException;

class JsonException extends BaseException{
    public function __construct(string $message, int $statusCode){
        parent::__construct($message, $statusCode);
        $this->exceptionType = 'Invalid Json Error';
    }
}