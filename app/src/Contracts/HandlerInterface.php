<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 2/19/19
 * Time: 3:29 PM
 */

namespace App\Src\Contracts;


interface HandlerInterface
{
    public function handle($command);
}