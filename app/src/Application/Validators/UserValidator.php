<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 2/21/19
 * Time: 11:27 AM
 */
namespace App\Src\Application\Validators;


use Illuminate\Validation\Rule;

class UserValidator extends BaseValidator
{
    /**
     * Array of validation rules
     *
     * @var array
     */

    //Reglas de validacion para model User y Profile
    protected $rules = [
        'name'=>'required',
        'email' => 'required|email',
        'profile.lastname' => 'required',
        'profile.mobile' => 'required'
    ];

    public function validate($command)
    {
        if(isset($command->getAttributes()['id']))
            $this->rules['email'] = $this->rules['email']."|".Rule::unique('users')->ignore($command->getAttributes()['id']);
        else
            $this->rules['email'] = $this->rules['email']."|unique:users";

        parent::validate($command);
    }

    public function errors()
    {
        return $this->validator->errors();
    }

}