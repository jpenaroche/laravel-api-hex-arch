<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 3/27/19
 * Time: 3:40 PM
 */

namespace App\Events;


class BaseModelEvent
{
    public $model;

    /**
     * BaseModelEvent constructor.
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

}