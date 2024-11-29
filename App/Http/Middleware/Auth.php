<?php

namespace App\Http\Middleware;

use App\Core\Request\Request;

class Auth{
    public function handle(Request $request){
        //example middleware to manage the auth
        //this function is an pre-processor, so this is executed before the controller
    }
}