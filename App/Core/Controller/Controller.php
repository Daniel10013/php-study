<?php

namespace App\Core\Controller;

use App\Core\Business\Business;
use App\Core\Request\Request;

class Controller{

    protected ?Business $business;
    protected Request $request;

    public function __construct(Request $request){
        $this->request = $request;
        $this->setBusiness();
    }

    private function setBusiness(): void{
        $businessPath = str_replace("Controller", "Business", $this::class);
        $businessPath = str_replace("Http\\", "", $businessPath);
        if(class_exists($businessPath)){
            $this->business = new $businessPath;
            return;
        }

        $this->business = null;
    }
}