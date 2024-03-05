<?php

namespace App\Router\Params\Body;

class BodyParams{
    public function __construct() {
        //treat json here;
        var_dump(file_get_contents('php://input'));die;
    }
}