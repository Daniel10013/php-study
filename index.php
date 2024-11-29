<?php 
require_once 'helper.php';
require_once 'autoload.php';
require_once __DIR__ . '/App/Config/config.php';

use App\Core\Router\AppRouter;
use App\Lib\Database\DatabaseConnection;
use App\Exceptions\Database\DatabaseConnection as DatabaseException;

try{
    (new DatabaseConnection())->checkDatabaseConnection();
}
catch(DatabaseException $e){
    echo $e->getExceptionResponse();
    exit();
}

new AppRouter();