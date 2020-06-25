<?php

namespace App\Src\Domain\Models;

use App\Src\Application\Utils\FillRelationships;
use App\Src\Application\Utils\MediaManager;
use App\Src\Application\Utils\ModelActions;
use App\Src\Application\Utils\SetParameters;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Plank\Mediable\Mediable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    //TODO Buscar una via para la inyeccion de traits sin que queden atados los modelos con la capa Framework
    use HasApiTokens, HasRoles, Notifiable, Mediable, FillRelationships, MediaManager, ModelActions;
    use SetParameters {
        save as saveAll;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'locale', 'is_admin', 'active', 'pos'
    ];


    //Arreglo de relaciones que se obtienen por el arreglo asociativo para hacer un nested create

    protected $fillable_relations = ['profile'];

    //Cada modelo tiene que incluir este atributo para el acceso a las relaciones que se devuelven por la llamada a la api
    public static $relationships = ['roles', 'permissions', 'profile', 'media'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    public function getCreatedAtAttribute(){
        return Carbon::createFromFormat('Y-m-d H:i:s',$this->attributes['created_at'])->format('d/m/y');
    }

    public function profile()
    {
        return $this->hasOne('App\Src\Domain\Models\Profile');
    }

    public function save(array $attributes = [], $load = true)
    {
        //Guardo medias y demas atributos
        $this->saveAll($attributes, false);

        //Logica especifica del model user
        if(isset($attributes['roles']))
            $this->roles()->sync($attributes['roles']);
        if(isset($attributes['permissions']))
            $this->permissions()->sync($attributes['permissions']);

        return $this->load(self::$relationships);
    }
}
