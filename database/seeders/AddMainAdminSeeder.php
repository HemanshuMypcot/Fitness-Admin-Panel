<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Admin;

class AddMainAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$role_id = Role::first()->id;
        $admin = [
            'admin_name' => 'admin',
            'nick_name' => 'admin',
            'email' => 'admin@admin.com',
            'country_id' => 91,
            'phone' => rand(1111111111, 9999999999),
            'password' => md5('admin@admin.com123456'),
            'address' => 'Address',
            'role_id' => $role_id
        ];
        Admin::firstOrCreate($admin);
    }
}
