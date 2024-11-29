<?php

namespace App\Core\Router;

use App\Core\Router\Route;
use App\Lib\DefaultResponses;
use App\Core\Exceptions\BaseException;

class AppRouter
{
    // The main route is the route file name ex: users.php

    private string $mainRoute;
    private string $subRoute;
    private string $fullRoute;

    public function __construct()
    {
        $this->setFullRoute();
        $this->setMainRoute();
        $this->setSubRoute();
        $this->requestMainRoute();
    }

    private function setFullRoute(): void
    {
        $this->fullRoute = array_key_exists('url', $_GET) ? $_GET['url'] : '';
    }

    private function setMainRoute(): void
    {
        $fullRouteArray = explode('/', $this->fullRoute);
        $this->mainRoute = $fullRouteArray[0];
    }

    private function setSubRoute(): void
    {
        $limitedRouteArray = explode('/', $this->fullRoute, 2);
        $this->subRoute = !empty($limitedRouteArray[1]) ? $limitedRouteArray[1] : '';
    }

    private function requestMainRoute()
    {
        if ($this->mainRoute == '') {
            return $this->redirectToMainRoute();
        }

        $routeFile = 'App\\Http\\Routes\\' . $this->mainRoute . '.php';
        if ($this->routeFileExists($routeFile) == false) {
            DefaultResponses::serverNotFound();
        }

        ob_start();
        $this->executeRoute($routeFile);
        ob_end_clean();
    }

    private function executeRoute(string $routeFile): void {
        try{
            $route = new Route($this->subRoute);
            require_once($routeFile);

            if($route->hasExcutedRoute() == false){
                DefaultResponses::serverNotFound();
            }
        } catch (BaseException $exception){
            echo $exception->getExceptionResponse();
            exit();
        }
    }

    private function routeFileExists(string $routeFilePath): bool
    {
        return file_exists($routeFilePath);
    }

    private function redirectToMainRoute(): void
    {
        echo 'The API docs can be found on the read.me file!';
    }
}