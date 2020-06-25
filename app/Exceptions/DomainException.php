<?php

namespace App\Exceptions;

use App\Src\Framework\LaravelValidator;
use Exception;

class DomainException extends Exception
{
    protected $errors;

    protected $code;

    public function __construct($errors,$code = 401)
    {
        $this->errors = $errors;
        $this->code = $code;
    }

    public function render()
    {
        // return a json with desired format
        return response([
            "status" => 'ERROR',
            "message" => __("exceptions.domain.default"),
            "errors" => array($this->errors)
        ],$this->code);
    }
}