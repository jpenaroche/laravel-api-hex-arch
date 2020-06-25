<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 2/21/19
 * Time: 11:27 AM
 */
namespace App\Src\Application\Validators;


class RoleValidator extends BaseValidator
{
    /**
     * Array of validation rules
     *
     * @var array
     */

    //Reglas de validacion para model User y Profile
    protected $rules = [
        'name'=>'required',
        'permissions'=>'required'
    ];

    public function errors()
    {
        return $this->validator->errors();
    }

}