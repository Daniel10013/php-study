<?php

namespace App\Core\Request\Validator;

use stdClass;
use App\Core\Response\Response;

class RequestValidator{

    private array $rules;
    private array $dataToValidate;
    private string $typeInValidation;

    public function __construct(array $rules, stdClass $urlData,  stdClass $bodyData){
        $this->setDataToValidate($urlData, $bodyData);
        $this->setRules($rules);
        $this->validateData();
    }

    private function setRules(array $rules): void{
        $this->rules = $rules;
    }

    private function setDataToValidate(stdClass $urlData, stdClass $bodyData): void{
        $this->dataToValidate = array_merge((array)$urlData, (array)$bodyData);
    }
    
    private function validateData(): void{
        foreach($this->rules as $field => $ruleSet){
            if(array_key_exists($field, $this->dataToValidate) == false){
                // TERMINAR DE TESTAR DADOS
                $this->sendError("Field {$field} was not found in the received data!");
            }

            $this->typeInValidation = $ruleSet["type"];
            foreach($ruleSet as $type => $rule){
                $validationResult = $this->validate($type, $rule, $field);
                if($validationResult == false){
                    $errorMsg = $this->getValidationErrorMessage($type, $rule, $field);
                    $this->sendError($errorMsg) ;
                }
            }
        }
        die();
    }

    private function validate(string $key, string $rule, string $field): bool{
        $dataToValidate = $this->dataToValidate[$field];

        $typesNotValidateRange = ["date", "boolean", "phone"];
        $canValidateRange = in_array($this->typeInValidation, $typesNotValidateRange) == true ? false : true;

        if($canValidateRange == true && ($key == "min" || $key == "max")){
            return Validations::range($rule, $dataToValidate, $key);
        }

        if(($key == "min" || $key == "max") && $canValidateRange == false){
            return true;
        }

        if($key == "required"){
            return Validations::required($dataToValidate, $rule);
        }

        $validations = [
            "type" => [
                "string" => Validations::string($dataToValidate),
                "int" => Validations::int($dataToValidate),
                "number" => Validations::number($dataToValidate),
                "email" => Validations::email($dataToValidate),
                "boolean" => Validations::boolean($dataToValidate),
                "phone" => Validations::phone($dataToValidate),
                "date" => Validations::date($dataToValidate),
            ]
        ];

        return $validations[$key][$rule];
    }

    private function getValidationErrorMessage(string $type, string $rule, string $field): string{
        $typeErrorMessages = [
            "string" => "string",
            "int" => "integer number",
            "number" => "valid number",
            "email" => "valid e-mail!",
            "boolean" => "boolean",
            "phone" => "valid phone",
            "date" => "valid date",
        ];

        $rangeErrorMessages = [
            "string" => [
                "min" => "{$field} lenght needs to be higher than $rule!",
                "max" => "{$field} lenght needs to be lower than $rule!"
            ],
            "number" => [
                "min" => "{$field} value needs to be higher than $rule!",
                "max" => "{$field} value needs to be lower than $rule!"
            ], 
        ];
        
        
        if($type == 'type'){
            return "The field {$field} needs to be an {$typeErrorMessages[$rule]}";
        }
        if($type == "min" || $type == "max"){
            $fieldType = gettype($this->dataToValidate[$field]);
            $fieldType = is_numeric($this->dataToValidate[$field]) || $fieldType == "integer" ?  "number" : "string";
            return $rangeErrorMessages[$fieldType][$type];
        }
        
        $requiredErrorMessage = "The field {$field} can't be empty!";
        return $requiredErrorMessage;
    }

    private function sendError(string $message): void {
        Response::send([
            "error" => [
                "type" => "request_error",
                "message" => $message,
                "status_code" => BAD_REQUEST
            ]
        ], BAD_REQUEST);
    }
}