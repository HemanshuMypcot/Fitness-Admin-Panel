<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class AddContactPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parent_permission = [
            'name' => 'Contacts',
            'codename' => 'contact',
            'parent_status' => 'parent',
            'description' => '',
            'status' => '1'
        ];
        $result = Permission::firstOrCreate($parent_permission);

        $permissions = [
	        [
	            'name' => 'View',
	            'codename' => 'contact_view',
	            'parent_status' => $result->id,
	            'description' => '',
	            'status' => '1'
	        ],
        ];

        foreach ($permissions as $permission) {
        	Permission::firstOrCreate($permission);
        }
    }
}
