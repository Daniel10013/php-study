<?php

namespace App\Router;

use App\Lib\HttpStatus;
use App\Lib\HtmlResponses;

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
        if (!$this->routeFileExists()) {
            return $this->redirectToNotFound();
        }

        $routeFile = 'App\\Routes\\' . $this->mainRoute . '.php';
        extract(['subRoute' => $this->subRoute]);
        ob_start();
        require_once($routeFile);
        ob_end_clean();
    }

    private function routeFileExists(): bool
    {
        $baseDir = str_replace('Router', 'Routes', __DIR__);
        $routeFilePath = $baseDir . '/' . $this->mainRoute . '.php';
        if (!file_exists($routeFilePath)) {
            return false;
        }

        return true;
    }

    private function redirectToMainRoute(): void
    {
        echo 'The API docs can be found on the read.me file!';
    }

    private function redirectToNotFound(): void
    {
        echo HtmlResponses::serverNotFound();
        HttpStatus::changeHttpStatus(NOT_FOUND);
        exit();
    }
}