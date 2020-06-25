<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreatePermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:permissions {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $section = $this->argument('name');

        // Limpiar cache de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //Crear permisos para seccion
        $permisos =  collect([]);
        foreach(['listar','nuevo','editar','crear','actualizar','borrar'] as $action){
            $permisos->push("$action-$section");
            Permission::create(['name' => "$action-$section",'guard_name'=>'admin']);
        }

        Role::create(['name' => "rol_$section",'guard_name'=>'admin'])->givePermissionTo($permisos->toArray());

        $this->info('Generados todos los permisos y roles para la sección');
    }
}
