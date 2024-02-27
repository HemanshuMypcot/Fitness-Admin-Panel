<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AddEmailNotificationData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('email_notifications')->insert([
            'id' => 1,
            'mail_key' => 'FORGOT_PASSWORD',
            'user_type' => 'all',
            'language_id' => 1,
            'title' => 'FORGOT PASSWORD link for admin user',
            'from_name' => 'Fitness Studio Team',
            'from_email' => 'info@mypcot.com',
            'to_name' => null,
            'cc_email' => null,
            'subject' => 'Forgot Password Notification - Fitness Studio Team',
            'label' => 'Fitness Studio admin registration',
            'content' => '<p><span style="font-weight: bold;">Hi $$admin_name$$,</span></p><p><span style="font-weight: bold;">You have recently requested a forgot password link. We\'ve received the request and your password request link has been processed.</span></p><p><span style="font-weight: bold;">Your reset password link is : <a href=$$url$$ tab=\"_new\">Click Here</a><br></span></p><p><span style="font-weight: bold;">Kindly click on the above link to reset your password.</span></p><br/><p><span style="font-weight: bold;">Thanks,</span></p><p><span style="font-weight: bold;">$$from_name$$</span></p>',
            'trigger' => 'batch',
            'status' => 1,
            'created_by' => 0,
            'updated_by' => 0,
            'deleted_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
