<?php

namespace App\Core\Request;

use stdClass;
use App\Core\Exceptions\BaseException;
use App\Core\Router\Params\Body\BodyParams;
use App\Core\Router\Params\Url\RouteUrlParams;
use App\Core\Request\Validator\RequestValidator;

class Request{

    public stdClass $body;
    public stdClass $header;
    public stdClass $url;

    public function __construct(BodyParams $bodyData, RouteUrlParams $urlData){
        $this->setRequestHeader();
        $this->setBody($bodyData);
        $this->setUrlParams($urlData);
    }

    private function setUrlParams(RouteUrlParams $urlData): void{
        if(empty($urlData->urlParams)){
            return;
        }   

        $std = new stdClass();
        foreach($urlData->urlParams as $attribute => $data){
            if(is_numeric($data) && is_int((int)$data)){
                $std->$attribute = (int)$data;
                continue;
            }
            $std->$attribute = $data; 
        }
        $this->url = $std;
    }

    protected function rules(): array{
        return [];
    }

    private function setBody(BodyParams $bodyData): void{
        $this->body = $bodyData->getBodyData();
    }

    private function setRequestHeader(): void{
        $header = getallheaders();
        $std = new stdClass();
        foreach($header as $attribute => $headerData){
            $std->$attribute = $headerData; 
        }
        $this->header = $std;
    }

    public function validate($requestMethod): void{
        $rules = $this->rules();
        if(array_key_exists($requestMethod, $rules) == false){
            return;
        }
        if(gettype($rules[$requestMethod]) != "array"){
            throw new BaseException("Invalid ruleset for request!", BAD_REQUEST, "invalid_ruleset");
        }

        new RequestValidator($rules[$requestMethod], $this->url, $this->body);
    }
}