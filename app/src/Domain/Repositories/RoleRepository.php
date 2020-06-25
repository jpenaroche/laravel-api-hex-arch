<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 2/20/19
 * Time: 3:14 PM
 */

namespace App\Src\Domain\Repositories;


use App\Src\Contracts\RepositoryInterface;
use App\Src\Domain\Models\Role;

class RoleRepository extends BaseRepository
{
    private static $repository;

    public function __construct($time, RepositoryInterface $base)
    {
        $base->setModel(Role::class);
        parent::__construct($base);
    }

    public static function singleton($time,$base)
    {
        if (!isset(self::$repository)) {
            $class = __CLASS__;
            self::$repository = new $class($time,$base);
        }
        return self::$repository;
    }

    //Metodos especificos del repositorio User
}