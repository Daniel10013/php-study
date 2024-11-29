<?php

namespace App\Core\Business;

use App\Core\Model\Model;

class Business{

    public ?Model $model;

    public function __construct(){
        $modelPath = str_replace("Business", "Model", $this::class);
        if(class_exists($modelPath)){
            $this->model = new $modelPath;
            return;
        }

        $this->model = null;
    }
}   