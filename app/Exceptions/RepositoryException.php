<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 2/21/19
 * Time: 2:15 PM
 */

namespace App\Exceptions;


use Exception;

class RepositoryException extends Exception
{

    protected $code;
    protected $message;

    public function __construct($message,$code = 500)
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function render()
    {
        // return a json with desired format
        return response([
            "status" => 'ERROR',
            "message" => __("exceptions.repository.default"),
            "errors" => array($this->message)
        ],$this->code);
    }
}