<?php

namespace App\Router\Validations;

use App\Exceptions\RouteException;
use App\Router\Validations\ParamsValidations;

class RouteValidations{
    public static function routeHttpMethodIsDifferentFromRequest(string $httpMethod, string $requestedHttpMethod): bool{
        return $httpMethod != $requestedHttpMethod;
    }

    public static function requestRouteAndFunctionRoutesAreEqual(string $functionRoute, string $requestedRoute): bool{
        $splitedRoute = self::getSplitedRoute($functionRoute);
        $splitedRequestedRoute = self::getSplitedRoute($requestedRoute);
        if(!ParamsValidations::routesHaveSameLenght($splitedRequestedRoute, $splitedRoute)){
            return false;
        }        
        
        foreach($splitedRoute as $key => $route){
            if(ParamsValidations::isParam($route)){
                $splitedRequestedRoute[$key] = $route;
            }
        }

        $implodedRequestedRoute = implode('/', $splitedRequestedRoute);
        return $functionRoute === $implodedRequestedRoute;
    }

    private static function getSplitedRoute(string $route): array{
        return explode('/', $route);
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
        if(class_exists($class)){
            $classInstance = new $class;
            return $classInstance instanceof \App\Controller\Controller;
        }
        return false;
    }
}