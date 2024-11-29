<?php 

namespace App\Routes;  
use App\Http\Controller\UsersController;

// $route->get('daniel/:id', [Users::class, "listUsers"], []); 
// $route->get('veiculo/:marca/:id/:tipo_venda', [Users::class, "listarUsuario"], []);

$route->post('test/:id', [UsersController::class, "listUsers"], ["Auth"]);

