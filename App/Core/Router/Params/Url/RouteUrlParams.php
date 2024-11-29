<?php

namespace App\Core\Router\Params\Url;


use App\Exceptions\RouteException;
use App\Core\Router\Validations\ParamsValidations as ParamValidator;

class RouteUrlParams{

    public array $urlParams;

    public function setUrlParams(string $route, string $requestedRoute):void {
        try{
            $this->urlParams = $this->getTreatedUrlParams($route, $requestedRoute);
        }
        catch (RouteException $e){
            echo $e->getExceptionResponse();
            exit();
        }
    }

    public function getUrlParams():array {
        return $this->urlParams;
    }

    public function getTreatedUrlParams(string $route, string $requestedRoute): array {
        $splitedRoute = $this->getSplitedRoute($route);
        $splitedRequestedRoute = $this->getSplitedRoute($requestedRoute);
        
        if(!ParamValidator::routesHaveSameLenght($splitedRequestedRoute, $splitedRoute)){
            throw new RouteException('Invalid route requested', NOT_ACCEPTABLE);
        }
        
        $getParams = [];
        foreach($splitedRoute as $key => $route){
            if(ParamValidator::isParam($route)){
                $paramName = ltrim($route, ':');
                $requestedRouteValue = $splitedRequestedRoute[$key];
                $getParams[$paramName] = $requestedRouteValue;
            }
        }
    
        return $getParams;
    }
    
    private function getSplitedRoute(string $route): array{
        return explode('/', $route);
    }
}

