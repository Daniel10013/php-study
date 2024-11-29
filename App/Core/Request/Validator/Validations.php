<?php

namespace App\Core\Request\Validator;

use function PHPSTORM_META\type;

class Validations{

    public static function string(mixed $value): bool{
        return is_string($value);
    }

    public static function int(mixed $value): bool{
        return is_int($value);
    }

    public static function number(mixed $value): bool{
        return is_numeric($value);
    }   

    public static function email(mixed $value): bool{
        return filter_var($value, FILTER_VALIDATE_EMAIL);;
    } 

    public static function boolean(mixed $value): bool{
        return is_bool($value);
    } 

    public static function phone(mixed $value): bool{
        $pattern = '/^\(\d{2}\) \d{5}-\d{4}$/';
        return preg_match($pattern, $value);
    } 

    public static function date(mixed $value): bool{
        $pattern = '/^\d{2}\/\d{2}\/\d{4}$/';
        if (!preg_match($pattern, $value)) {
            return false; 
        }

        list($day, $month, $year) = explode('/', $value);
        if (!checkdate($month, $day, $year)) {
            return false;
        }
        return true;
    } 

    public static function required(mixed $value, bool $rule): bool{
        if($rule == true){
            return empty($value) == false;
        }
        return true;
    }

    public static function range(mixed $rule, mixed $value, string $type): bool{
        $typeToValidate = gettype($value);
        if(is_numeric($type) || $typeToValidate == "integer"){
            $typeToValidate = "number";
        }

        $rangeValidation = [
            "string" => [
                "min" => strlen($value) >= $rule,
                "max" => strlen($value) <= $rule,
            ],
            "number" => [
                "min" => $value >= $rule,
                "max" => $value <= $rule,
            ]
        ];

        return $rangeValidation[$typeToValidate][$type];
    } 
}