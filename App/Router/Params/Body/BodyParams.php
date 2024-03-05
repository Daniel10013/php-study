<?php

namespace App\Router\Params\Body;

use App\Lib\JSON;
use App\Exceptions\JsonException;

class BodyParams{

    private string $bodyData;

    public function __construct() {;
        try{
            $this->bodyData = $this->getDataFromRequestBody();
        } catch(JsonException $error){
            echo $error->getExceptionResponse();
            exit();
        }
    }

    public function getBodyData(): string{
        return $this->bodyData;
    }

    private function getDataFromRequestBody():string {
        $postData = file_get_contents('php://input');
        if(JSON::getJsonIfIsValid($postData) != NULL){
            return $postData;
        }
        throw new JsonException(json_last_error_msg(), BAD_REQUEST);
    }
}