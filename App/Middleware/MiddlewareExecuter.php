<?php

namespace App\Middleware;

class MiddlewareExecuter{

    public array $middlewaresToExecute;

    public function __construct(array $middlewares){
        if(!empty($middlewares)){
            $this->runMiddlewares($middlewares);
        }
    }

    private function runMiddlewares(array $middlewares){

        //validate middleware array key
        // key should have middleware name class, function
        //run middleware after validation
        //middleware can exit aplication if its results are not true
        //after running an middleware prepare request to send to controller
        //send request to controller instance and run the controller method

        try{

        }
        catch(\Exception $e){

        }
    }
}