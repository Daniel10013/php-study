<?php

namespace App\Lib;

class HtmlResponses{

    public static function serverNotFound(string $message = "Content Not Found on server"): string{
        return '<h1>Error - 404</h1><hr>' . $message;
    }
}