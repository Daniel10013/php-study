<?php

namespace App\Router;

use App\Exceptions\RouteException;
use App\Router\Validations\RouteValidations as Validator;
use App\Router\Params\Url\RouteUrlParams;
use App\Router\Params\Body\BodyParams;
use App\Lib\JSON;

class Route{
     
    private string  $requestHttpMethod;
    public string $requestedRoute;
    private string $httpMethodToExecute;
    private RouteUrlParams $requestUrlParams;
    private BodyParams $requestBody;

    public function __construct(string $requestedRoute) {
        $this->requestUrlParams = new RouteUrlParams();
        $this->requestBody = new BodyParams();
        $this->setRequestHttpMethod();
        $this->setRequestedRoute($requestedRoute);
    }

    private function setRequestHttpMethod(): void{
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $this->requestHttpMethod = !empty($httpMethod) ? $httpMethod : 'GET';
    }

    private function setRequestedRoute(string $requestedRoute): void{
        $this->requestedRoute = !empty($requestedRoute) ? $requestedRoute : '/';
    }

    public function get(string $route, array $routeMethod, array $middlewares = []){
        $this->httpMethodToExecute = 'GET';
        $this->executeRoute($route, $routeMethod, $middlewares);
    } 

    public function post(string $route, array $routeMethod, array $middlewares = []){
        $this->httpMethodToExecute = 'POST';
        $this->executeRoute($route, $routeMethod, $middlewares);

    }

    public function put(string $route, array $routeMethod, array $middlewares = []){
        $this->httpMethodToExecute = 'PUT';
        $this->executeRoute($route, $routeMethod, $middlewares);

    }

    public function patch(string $route, array $routeMethod, array $middlewares = []){
        $this->httpMethodToExecute = 'PATCH';
        $this->executeRoute($route, $routeMethod, $middlewares);

    }

    public function delete(string $route, array $routeMethod, array $middlewares = []){
        $this->httpMethodToExecute = 'DELETE';
        $this->executeRoute($route, $routeMethod, $middlewares);
    }

    private function executeRoute(string $route, array $routeMethod, array $middlewares = []){
        if($this->isToExecuteRoute($route)){
            $this->requestUrlParams->setUrlParams($route, $this->requestedRoute);
            // $this->requestBody->set
            var_dump($this->requestUrlParams->getUrlParams());die;
            if(!empty($middlewares)){
                $this->runMiddlewares($middlewares);
                // Run
            }
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
        catch(RouteException $e){

        }
    }

    private function isToExecuteRoute(string $functionRoute): bool{
        if(Validator::routeHttpMethodIsDifferentFromRequest($this->httpMethodToExecute, $this->requestHttpMethod)){
            return false;
        }
        return Validator::requestRouteAndFunctionRoutesAreEqual($functionRoute, $this->requestedRoute);
    }
    
}