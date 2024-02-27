<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RevertDefaultRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
        	'Admin'
        ];
        $roleData = Role::whereIn('role_name', $roles)->pluck('id');
        foreach ($roleData as $role) {
        	Role::find($role)->delete();
        }
    }
}
