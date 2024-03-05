<?php

namespace App\Lib;

class HttpStatus{

    public static function changeHttpStatus(int $status): void{
        http_response_code($status);
    }
}