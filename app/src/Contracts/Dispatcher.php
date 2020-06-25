<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 2/20/19
 * Time: 11:31 AM
 */

namespace App\Src\Contracts;

interface Dispatcher
{
    public function dispatch($event);

    public function broadcast($event, $to_others = false);
}