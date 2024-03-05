<?php 
require_once 'autoload.php';
require_once __DIR__ . '/App/Config/config.php';

use App\Lib\Database\DatabaseConnection;
use App\Exceptions\Database\DatabaseConnection as DatabaseException;
use App\Router\AppRouter;

try{
    (new DatabaseConnection())->checkDatabaseConnection();
}
catch(DatabaseException $e){
    echo $e->getExceptionResponse();
    exit();
}

new AppRouter();