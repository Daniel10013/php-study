<?php 

namespace App\Routes;  
use App\Controller\Users;

$route->get('daniel/:id', [Users::class, "listarUsuario"], []);
$route->post('lkz/:nome/:id', [Users::class, "listarUsuario"], []);