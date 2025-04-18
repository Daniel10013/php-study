<?php

namespace App\Exceptions\Database;

use App\Core\Exceptions\BaseException;

class DatabaseConnection extends BaseException{

    public function __construct(string $message, int $statusCode){
        parent::__construct($message, $statusCode);
        $this->exceptionType = 'database';
    }
}