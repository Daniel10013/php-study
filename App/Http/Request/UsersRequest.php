<?php

namespace App\Http\Request;

use App\Core\Request\Request;

class UsersRequest extends Request{

    /* 
        For better explanation on how to create request Rules, check the documentation!
        Rules array example:
        [
            "ControllerMethodName" => 
            [
                "fieldName" => [
                    "type" => "string | int | email | boolean | phone | date ", 
                    "required" => true | false |, 
                    "min" => 100,
                    "max" => 250
                ]
            ]
        ]
    */  
    protected function rules(): array{
        return [
        ];
    }
}