<?php

namespace App\Lib;

use stdClass;
use App\Exceptions\JsonException;

class JSON{

    public static function response(array $content): string{
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($content);
    }

    public static function decode(string $text): stdClass | NULL{
        $decodedText = json_decode($text);
        if(json_last_error()){
            throw new JsonException(json_last_error_msg(), BAD_REQUEST);
        }
        return $decodedText == null ? "" : $decodedText;
    }
}