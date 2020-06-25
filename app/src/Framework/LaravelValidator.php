<?php

/**
 * This file contains a class that abstracts Laravel's Validator facade. Extend
 * this class and declare your errors and messages as static variables to clean
 * up validation in controllers.
 *
 */

namespace App\Src\Framework;

use App\Src\Contracts\Validator as ValidatorContract;
use Illuminate\Support\Facades\Validator as V;

/**
 * This class abstracts Laravel's Validator facade. Extend this and declare
 * your errors and messages as static variables to clean up validation in
 * controllers.
 *
 * @author John Arstingstall <jarstingstall@indatus.com>
 */
class LaravelValidator implements ValidatorContract
{
    /**
     * Validation errors
     *
     * @var Illuminate\Support\MessageBag
     */
    protected $errors;

    protected $validator;

    /**
     * LaravelValidator constructor.
     */
    public function __construct($validator = null)
    {
        $this->validator = $validator;
    }


    /**
     * Abstracts Laravel's Validator::make()
     *
     * @param array $input Array of input data
     *
     * @return boolean
     */
    public function validate($input)
    {
        $messages = (isset(static::$messages)) ? static::$messages : array();

        $validator = V::make($input, $this->validator->getRules(), $messages);

        if ($validator->fails()) {
            $this->errors = $validator->messages();

            return false;
        }

        return true;
    }

    /**
     * Getter method for the errors property
     *
     * @return Illuminate\Support\MessageBag
     */
    public function errors()
    {
        return $this->errors;
    }
}
