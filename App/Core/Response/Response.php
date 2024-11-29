<?php

namespace App\Core\Response;

use App\Lib\HttpStatus;
use App\Lib\JSON;

class Response{

    public static function send(array $json, int $statusCode):void {
        HttpStatus::changeHttpStatus($statusCode);
        $responseText = JSON::response($json);
        echo $responseText;
        exit;
    }
}