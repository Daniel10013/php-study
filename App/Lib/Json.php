<?php

namespace App\Lib;

use stdClass;

class JSON{

    public static function response(array $content): string{
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($content);
    }

    public static function getJsonIfIsValid(string $text): stdClass | NULL{
        return json_decode($text);
    }
}