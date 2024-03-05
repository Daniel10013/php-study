<?php 

namespace App\Routes;  
use App\Router\Route;

$route = new Route($subRoute);
$route->get(':id', [], []);
$route->get('daniel/id/:id', [], []);
$route->get('daniel/teste/:id', [], []);
$route->get('usuario/:nome/:id', [], []);