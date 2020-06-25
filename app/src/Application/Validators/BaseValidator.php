<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 2/21/19
 * Time: 11:27 AM
 */
namespace App\Src\Application\Validators;

use App\Exceptions\ValidationException;
use App\Src\Contracts\Validator;
use App\Src\Framework\LaravelValidator;

class BaseValidator implements Validator
{
    protected $validator;

    public function validate($command)
    {
        $this->validator = new LaravelValidator($this);

        if(!$this->validator->validate($command->getAttributes()))
            throw new ValidationException($this->validator);
    }

    public function errors()
    {
        return $this->validator->errors();
    }

    public function getRules(){
        return $this->rules;
    }
}