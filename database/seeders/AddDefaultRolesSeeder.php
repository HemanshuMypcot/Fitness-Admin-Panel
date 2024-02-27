<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class AddDefaultRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = [
            [
                'role_name' => 'Admin',
                'permission' => []
            ],
            [
                'role_name' => 'Staff',
                'permission' => []
            ]
        ];
        foreach ($role as $data){
            Role::create($data);
        }
    }
}
