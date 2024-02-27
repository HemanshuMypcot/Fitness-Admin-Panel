<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class RevertMainAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
        	'admin@admin.com'
        ];
        $adminData = Admin::whereIn('email', $admins)->pluck('id');
        foreach ($adminData as $admin) {
        	Admin::find($admin)->delete();
        }
    }
}
