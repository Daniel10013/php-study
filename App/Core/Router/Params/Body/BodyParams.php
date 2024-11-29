<?php

namespace App\Core\Router\Params\Body;

use stdClass;
use App\Lib\JSON;
use App\Exceptions\JsonException;

class BodyParams{

    private stdClass $bodyData;

    public function __construct() {;
        try{
            $this->bodyData = $this->getDataFromRequestBody();
        } catch(JsonException $error){
            echo $error->getExceptionResponse();
            exit();
        }
    }

    public function getBodyData(): stdClass{
        return $this->bodyData;
    }

    private function getDataFromRequestBody():stdClass {
        $requestBody = file_get_contents('php://input');
        if(empty($requestBody) == true){
            return new stdClass;
        }
        $bodyData = JSON::decode($requestBody);
        return $bodyData;
    }
}