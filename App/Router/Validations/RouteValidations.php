<?php

namespace App\Router\Validations;

class RouteValidations{
    public static function routeHttpMethodIsDifferentFromRequest(string $httpMethod, string $requestedHttpMethod): bool{
        return $httpMethod != $requestedHttpMethod;
    }
    
    public static function routesHaveSameLenght(array $requestedRoute, array $setedRoute): bool{
        $lengthRequestedRoute = count($requestedRoute);
        $lengthSetedRoute = count($setedRoute);
        return $lengthSetedRoute == $lengthRequestedRoute;
    }
    
    public static function routeHasParams($route): bool{
        return str_contains($route, ':');
    }
    
    public static function isParam(string $splitedRoutePart): bool{
        if(strlen($splitedRoutePart) == 1){
            return false;
        }
        return self::routeHasParams($splitedRoutePart);
    }

    public static function requestRouteAndFunctionRoutesAreEqual(string $functionRoute, string $requestedRoute): bool{
        $splitedRoute = self::getSplitedRoute($functionRoute);
        $splitedRequestedRoute = self::getSplitedRoute($requestedRoute);
        if(!self::routesHaveSameLenght($splitedRequestedRoute, $splitedRoute)){
            return false;
        }        
        
        foreach($splitedRoute as $key => $route){
            if(self::isParam($route)){
                $splitedRequestedRoute[$key] = $route;
            }
        }

        $implodedRequestedRoute = implode('/', $splitedRequestedRoute);
        return $functionRoute === $implodedRequestedRoute;
    }

    private static function getSplitedRoute(string $route): array{
        return explode('/', $route);
    }
}