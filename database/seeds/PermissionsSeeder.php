<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpiar cache de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => "asignar-roles",'guard_name'=>'api']);
        //Crear permisos para seccion administradores y roles
        $pos = 0;
        foreach(['roles','administradores','portadas'] as $key => $section){
            $permisos =  collect([]);
            foreach(['listar','nuevo','editar','crear','actualizar','borrar'] as $action){
                $permisos->push("$action-$section");
                Permission::create(['name' => "$action-$section",'guard_name'=>'api','pos'=>++$pos]);
            }
            Role::create(['name' => "rol_$section",'guard_name'=>'api','pos'=>++$key])->givePermissionTo($permisos->toArray());
        }
    }
}
