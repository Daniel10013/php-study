<?php

namespace App\Exceptions;

use App\Core\Exceptions\BaseException;

class RouteException extends BaseException{

    public function __construct(string $message, int $statusCode){
        parent::__construct($message, $statusCode);
        $this->exceptionType = 'Invalid route typed';
    }
}