<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 4/25/19
 * Time: 8:22 AM
 */

namespace App\Src\Domain\Models;

use \Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = ['name', 'description', 'active'];

    public static $relationships = ['permissions'];

    public function save(array $attributes = [])
    {
        $this->attributes['guard_name'] = config('permission.default_guard');
        parent::save();
        $this->permissions()->sync($attributes['permissions']);

        return $this->load(self::$relationships);
    }
}