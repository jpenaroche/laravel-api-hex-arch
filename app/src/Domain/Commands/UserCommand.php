<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 2/19/19
 * Time: 3:28 PM
 */

namespace App\Src\Domain;

class UserCommand extends BaseCommand
{
    protected $operation;
    /**
     * CreateCommand constructor.
     */
    public function __construct($operation = 'do',$attributes = [])
    {
        parent::__construct($attributes);
        if(isset($attributes['password']))
            $this->attributes['password'] = bcrypt($attributes['password']);
        $this->operation = $operation;
    }

    public function getOperation()
    {
        return $this->operation;
    }

    public function setOperation($operation)
    {
        $this->operation = $operation;
    }

}