<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 3/26/19
 * Time: 1:23 PM
 */

namespace App\Src\Framework;


use App\Src\Contracts\Dispatcher;

class LaravelDispatcher implements Dispatcher
{

    public function dispatch($event)
    {
        event($event);
    }

    public function broadcast($event, $to_others = false)
    {
        if ($to_others)
            broadcast($event)->toOthers();
        else
            broadcast($event);
    }
}