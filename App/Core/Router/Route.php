<?php

namespace App\Core\Router;

use App\Core\Request\Request;
use App\Exceptions\RouteException;
use App\Core\Exceptions\BaseException;
use App\Core\Router\Params\Body\BodyParams;
use App\Core\Router\Params\Url\RouteUrlParams;
use App\Core\Router\Validations\RouteValidations as Validator;

class Route{
     
    private BodyParams $requestBody;
    private RouteUrlParams $requestUrlParams; 
    private Request $request;
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
        $this->setRequestedRoute($requestedRoute);  
        $this->setHasExecutedRoute(false);
        $this->requestUrlParams = new RouteUrlParams();
        $this->requestBody = new BodyParams();
        $this->setRequestHttpMethod();
    }

    public function __call(string $method, mixed $params): void{
        if(array_key_exists($method, $this->httpMethods) == false){
            throw new RouteException("Invalid HTTP Method!", INTERNAL_SERVER_ERROR);
        }

        if(Validator::calledFunctionHasValidParameters($params)){
            $this->httpMethodToExecute = $this->httpMethods[$method];
            $middlewares = isset($params[2]) ? $params[2] : [];
            $this->executeRoute($params[0], $params[1], $middlewares);
        }
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

    private function executeRoute(string $route, array $toExecute, array $middlewares): void{
        if($this->isToExecuteRoute($route) == false){
            return;
        }

        $this->requestUrlParams->setUrlParams($route, $this->requestedRoute);
        $controller = $toExecute[0];
        $method = $toExecute[1];
        
        $this->getRequestObject($controller);
        $this->request = $this->getRequestObject($controller);
        $this->request->validate($method);
        
        $this->multiMiddlewares($middlewares);
        $this->setHasExecutedRoute(true); 
        (new $controller($this->request))->$method();
    }

    private function multiMiddlewares(array $middlewares){
        foreach($middlewares as $middleware){
            $this->executeMiddleware($middleware);
        }
    }

    private function executeMiddleware(string $middleware): void{
        $middlewarePath = "App\Http\Middleware\\$middleware";
        if(class_exists($middlewarePath) == false){
            throw new BaseException("Middleware don't exist", BAD_REQUEST, "Middleware");
        }
        
        (new $middlewarePath())->handle($this->request); 
    }

    private function isToExecuteRoute(string $functionRoute): bool{
        if(Validator::routeHttpMethodIsDifferentFromRequest($this->httpMethodToExecute, $this->requestHttpMethod)){
            return false;
        }
        return Validator::requestRouteAndFunctionRoutesAreEqual($functionRoute, $this->requestedRoute);
    }
    
    private function getRequestObject(string $controllerPath): Request{
        $requestPath = str_replace("Controller", "Request", $controllerPath);
        if(class_exists($requestPath) == false){
            return new Request($this->requestBody, $this->requestUrlParams);
        }

        return new $requestPath($this->requestBody, $this->requestUrlParams);
    }   
}