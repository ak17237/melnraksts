<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = new \App\Role();
        $role_user->Name = 'User';
        $role_user->Description = 'Parasts lietotājs';
        $role_user->save();
        
        $role_admin = new \App\Role();
        $role_admin->Name = 'Admin';
        $role_admin->Description = 'Administrātors';
        $role_admin->save();
    }
}
