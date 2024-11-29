<?php 

namespace App\Routes;  
use App\Http\Controller\UsersController;

$route->post('test/:id', [UsersController::class, "listUsers"], ["Auth"]);

