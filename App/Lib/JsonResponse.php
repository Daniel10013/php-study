<?php

namespace App\Lib;

class JsonResponse{

    public static function jsonResponse(array $content): string{
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($content);
    }
}