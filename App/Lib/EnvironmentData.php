<?php

namespace App\Lib;

class EnvironmentData
{
    static function getEnvData(string $dataKey): string {
        $envData = parse_ini_file('.env');
        if(key_exists($dataKey, $envData)){
            return $envData[$dataKey];
        }
        return '';
    }
}
