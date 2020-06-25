<?php

namespace App\Src\Contracts;

interface Validator
{
    public function validate($rules);

    public function errors();
}