<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 4/3/19
 * Time: 2:25 PM
 */

namespace App\Src\Domain;


class BaseCommand
{
    protected $attributes = [];

    /**
     * BaseCommand constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function __call($method, $arguments)
    {
        $regexp = '/^set(?<attribute>[A-Z]\w+)$/';
        if (preg_match($regexp, $method, $matches) && count($arguments) == 1) {
            array_unshift($arguments, strtolower($matches['attribute']));
            return call_user_func_array(array($this, 'set'), $arguments);
        }
    }

    public function __get($name)
    {
        return $this->attributes[$name];
    }


    public function set($attribute,$value)
    {
        $this->attributes[$attribute] = $value;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }
}