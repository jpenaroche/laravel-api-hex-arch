<?php

namespace App\Src\Application\Utils;
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 3/19/19
 * Time: 2:24 PM
 */
trait ModelActions
{
    //Boot actions over models
    public static function boot()
    {
        parent::boot();
        //Inc pos attribute
        self::creating(function ($model){
            $model->pos = self::max('pos') + 1;
        });
    }
}