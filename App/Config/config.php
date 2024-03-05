<?php 

declare(strict_types=1);

use App\Lib\EnvironmentData;

define('DATABASE', EnvironmentData::getEnvData('DB_NAME'));
define('DB_HOST', EnvironmentData::getEnvData('DB_HOST'));
define('DB_USER', EnvironmentData::getEnvData('DB_USER'));
define('DB_PASSWORD', EnvironmentData::getEnvData('DB_PASSWORD'));

require_once('App\Config\httpStatusCodes.php');

?>