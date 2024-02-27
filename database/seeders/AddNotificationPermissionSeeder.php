<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class AddNotificationPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parent_permission = [
            'name' => 'Notification',
            'codename' => 'notification',
            'parent_status' => 'parent',
            'description' => '',
            'status' => '1'
        ];

        $result = Permission::firstOrCreate($parent_permission);
        $permissions = [
            [
                'name' => 'Add',
                'codename' => 'notification_add',
                'parent_status' => $result->id,
                'description' => '',
                'status' => '1'
            ],
            [
                'name' => 'Edit',
                'codename' => 'notification_edit',
                'parent_status' => $result->id,
                'description' => '',
                'status' => '1'
            ],
            [
                'name' => 'View',
                'codename' => 'notification_view',
                'parent_status' => $result->id,
                'description' => '',
                'status' => '1'
            ],
            [
                'name' => 'Send',
                'codename' => 'notification_send',
                'parent_status' => $result->id,
                'description' => '',
                'status' => '1'
            ]
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }
    }
}
