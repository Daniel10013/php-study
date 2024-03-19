<?php

namespace App\Router;

use App\Lib\JSON;
use App\Exceptions\RouteException;
use App\Middleware\MiddlewareExecuter;
use App\Router\Params\Body\BodyParams;
use App\Router\Validations\RouteValidations as Validator;
use App\Router\Params\Url\RouteUrlParams;

class Route{
     
    private BodyParams $requestBody;
    private RouteUrlParams $requestUrlParams; 

    private bool $hasExecutedRoute;
    public string $requestedRoute;
    private string $requestHttpMethod;
    private string $httpMethodToExecute;
    private array $httpMethods = [
        "get" => "GET",
        "post" => "POST",
        "put" => "PUT",
        "patch" => "PATCH",
        "delete" => "DELETE",
        "options" => "OPTIONS",
        "head" => "HEAD"
    ];

    public function __construct(string $requestedRoute) {
        $this->setHasExecutedRoute(false);
        $this->requestUrlParams = new RouteUrlParams();
        $this->requestBody = new BodyParams();
        $this->setRequestHttpMethod();
        $this->setRequestedRoute($requestedRoute);
    }

    private function setHasExecutedRoute(bool $value): void{
        $this->hasExecutedRoute = $value;
    }

    public function hasExcutedRoute(): bool{
        return $this->hasExecutedRoute;
    }

    private function setRequestHttpMethod(): void{
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $this->requestHttpMethod = !empty($httpMethod) ? $httpMethod : 'GET';
    }

    private function setRequestedRoute(string $requestedRoute): void{
        $this->requestedRoute = !empty($requestedRoute) ? $requestedRoute : '/';
    }

    public function __call(string $method, array $params){
        if(array_key_exists($method, $this->httpMethods) == false){
            throw new RouteException("Invalid HTTP Method!", INTERNAL_SERVER_ERROR);
        }

        if(Validator::calledFunctionHasValidParameters($params)){
            $this->httpMethodToExecute = $this->httpMethods[$method];
            $middlewares = isset($params[2]) ? $params[2] : [];
            $this->executeRoute($params[0], $params[1], $middlewares);
        }
    }

    private function executeRoute(string $route, array $toExecute, array $middlewares = []): void{
        if($this->isToExecuteRoute($route) == false){
            return;
        }
        new MiddlewareExecuter($middlewares);
        $this->setHasExecutedRoute(true); 
        $this->requestUrlParams->setUrlParams($route, $this->requestedRoute);
        $controller = $toExecute[0];
        $method = $toExecute[1];
        (new $controller())->$method;
    }



    private function isToExecuteRoute(string $functionRoute): bool{
        if(Validator::routeHttpMethodIsDifferentFromRequest($this->httpMethodToExecute, $this->requestHttpMethod)){
            return false;
        }
        return Validator::requestRouteAndFunctionRoutesAreEqual($functionRoute, $this->requestedRoute);
    }
    
}