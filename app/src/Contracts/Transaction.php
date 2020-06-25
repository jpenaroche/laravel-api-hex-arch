<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 2/20/19
 * Time: 11:31 AM
 */

namespace App\Src\Contracts;

interface Transaction
{
    public function execute($closure);
}