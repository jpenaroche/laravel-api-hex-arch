<?php

namespace App\Exceptions;

use Exception;

class TransactionException extends Exception
{
    protected $code;
    protected $queryException;

    public function __construct($exception,$code = 500)
    {
        $this->code = $code;
        $this->queryException = $exception;
    }

    public function render()
    {
        // return a json with desired format
        return response([
            "status" => 'ERROR',
            "message" => __("exceptions.transaction.default"),
            "errors" => array($this->queryException->getMessage())
        ],$this->code);
    }
}