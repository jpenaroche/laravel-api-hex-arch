<?php

namespace App\Src\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['lastname','mobile','phone'];

    public function user(){
        return $this->belongsTo('App\Src\Domain\Models\User');
    }
}