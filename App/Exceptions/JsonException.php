<?php

namespace App\Exceptions;

use App\Exceptions\BaseException;

class JsonException extends BaseException{
    public function __construct(string $message, int $statusCode){
        parent::__construct($message, $statusCode);
        $this->exceptionType = 'Json Error';
    }
}