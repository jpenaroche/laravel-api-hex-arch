<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 2/20/19
 * Time: 2:35 PM
 */

namespace App\Src\Contracts;


interface CRUDOperations
{
    public function show();

    public function list();

    public function create();

    public function update();

    public function delete();
}