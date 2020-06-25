<?php

use App\Src\Domain\Models\Profile;
use App\Src\Domain\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name'              => 'Soporte Sitios',
                'email'             => 'soporte@sitios.com.uy',
                'password'          => '$2y$10$F1JXpt1Y2H2M8WFOxY71iO2YVfxFwsLKLX0xi5HFP33zzyO3vigTO',
                'remember_token'    => NULL,
                'is_admin'          => true,
                'pos'               => 1,
                'created_at'        => '2017-07-06 23:36:15',
                'updated_at'        => '2017-07-06 23:36:15'
            ],
            [
                'name'              => 'Penna',
                'email'             => 'penna@localhost.com',
                'password'          => '$2y$10$YaF6cj2O3jFdX/okVAfcsuuLC4USRt2G15B3yRHjMltvlyfUOPENG',
                'remember_token'    => NULL,
                'is_admin'          => true,
                'pos'               => 2,
                'created_at'        => '2017-07-06 23:36:15',
                'updated_at'        => '2017-07-06 23:36:15'
            ]
        ]);

        $role = Role::firstOrCreate(['guard_name' => 'api', 'name' => 'super-admin']);

        User::get()->each(function($admin) use($role){
            $admin->assignRole($role);
            $admin->profile()->create([]);
        });
    }
}
