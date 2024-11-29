<?php

namespace App\Lib;

class DefaultResponses{

    public static function serverNotFound(string $message = "Content Not Found on server"): void{
        HttpStatus::changeHttpStatus(NOT_FOUND);
        echo JSON::response(
            array(
                "error" => [
                    "type" => 'not_found',
                    "message" => $message,
                    "status_code" => NOT_FOUND
                ]
            )
        );
        exit();
    }

    public static function internalServerError(string $message = "Internal server error"): void{
        HttpStatus::changeHttpStatus(INTERNAL_SERVER_ERROR);
        echo JSON::response(
            array(
                "error" => [
                    "type" => 'Server Error',
                    "message" => $message,
                    "status_code" => INTERNAL_SERVER_ERROR
                ]
            )
        );
        exit();
    }

    public static function badRequest(string $message = "Something went wrong with your request"): void{
        HttpStatus::changeHttpStatus(INTERNAL_SERVER_ERROR);
        echo JSON::response(
            array(
                "error" => [
                    "type" => 'Invalid data',
                    "message" => $message,
                    "status_code" => BAD_REQUEST
                ]
            )
        );
        exit();
    }
}