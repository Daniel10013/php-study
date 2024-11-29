<?php

namespace App\Core\Router\Validations;

use App\Exceptions\RouteException;
use App\Core\Router\Validations\ParamsValidations;
use App\Lib\DefaultResponses;

class RouteValidations{
    public static function routeHttpMethodIsDifferentFromRequest(string $httpMethod, string $requestedHttpMethod): bool{ 
        return $httpMethod != $requestedHttpMethod;
    }

    public static function requestRouteAndFunctionRoutesAreEqual(string $functionRoute, string $requestedRoute): bool{
        $splitedRoute = self::getSplitedRoute($functionRoute);
        $splitedRequestedRoute = self::getSplitedRoute($requestedRoute);

        if(ParamsValidations::routesHaveSameLenght($splitedRequestedRoute, $splitedRoute) == false){
            return false;
        }        
        
        foreach($splitedRoute as $key => $route){
            if(ParamsValidations::isParam($route)){
                $splitedRequestedRoute[$key] = $route;
            }
        }

        $implodedRequestedRoute = implode('/', $splitedRequestedRoute);
        return ltrim($functionRoute, "/") === $implodedRequestedRoute;
    }

    private static function getSplitedRoute(string $route): array{
        $dataToReturn = array_filter(explode('/', $route));
        return array_values($dataToReturn);
    }

    public static function calledFunctionHasValidParameters(array $params): bool{
        if(empty($params) || isset($params[1]) == false){
            throw new RouteException("Invalid Parameters for route Method!", INTERNAL_SERVER_ERROR);
        }
        
        if(isset($params[1][0]) == false){
            throw new RouteException("Missing Controller Name!", INTERNAL_SERVER_ERROR);
        }

        if(isset($params[1][1]) == false){
            throw new RouteException("Missing Controller Method!", INTERNAL_SERVER_ERROR);
        }

        if(self::receveidClassIsInstanceOfController($params[1][0]) == false){
            throw new RouteException("Class Should be an Controller Instance!", INTERNAL_SERVER_ERROR);
        }
        return true;
    }

    private static function receveidClassIsInstanceOfController(string $class){
        if(str_contains($class, "Controller") == false){
            DefaultResponses::badRequest("Controller class name should have the word 'Controller'");
        }
        if(class_exists($class)){
            return is_subclass_of($class ,"\App\Core\Controller\Controller");
        }
        return false;
    }
}