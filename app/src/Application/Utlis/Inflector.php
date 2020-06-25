<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 2/19/19
 * Time: 3:50 PM
 */

namespace App\Src\Application\Utils;


class Inflector
{
    private static $inflector;

    public static function singleton()
    {
        if (!isset(self::$inflector)) {
            $class = __CLASS__;
            self::$inflector = new $class();
        }
        return self::$inflector;
    }

    public function getHandler($command)
    {
        return 'App\Src\Application\Handlers\\' . str_replace('Command', 'Handler', class_basename($command));
    }

    public function getValidator($command)
    {
        return 'App\Src\Application\Validators\\' . str_replace('Command', 'Validator', class_basename($command));
    }
}