<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 4/25/19
 * Time: 8:22 AM
 */

namespace App\Src\Domain\Models;

use \Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    public static $relationships = ['roles'];
}