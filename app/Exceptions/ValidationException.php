<?php

namespace App\Exceptions;

use App\Src\Framework\LaravelValidator;
use Exception;

class ValidationException extends Exception
{
    protected $errors;

    protected $code;

    public function __construct(LaravelValidator $validator,$code = 401)
    {
        $this->validator = $validator;
        $this->code = $code;
    }

    public function render()
    {
        // return a json with desired format
        return response([
            "status" => 'ERROR',
            "message" => __("exceptions.validation.default"),
            "errors" => $this->validator->errors()
        ],$this->code);
    }
}