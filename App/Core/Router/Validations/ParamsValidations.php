<?php

namespace App\Core\Router\Validations;

class ParamsValidations{
    
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
}