<?php

namespace Database\Seeders;

use App\Models\Policy;
use Illuminate\Database\Seeder;

class PolicyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $policyData = [
            [
                'type'   => 'about',
                'en'       => [
                    'content' => '<p><strong>This is testing about us.</strong></p>'
                ],
                'hi'       => [
                    'content' => '<p><strong>This is testing about us.</strong></p>'
                ],
            ],
            [
                'type' => 'terms',
                'en'       => [
                    'content' => '<p><strong>This is testing terms & conditions.</strong></p>'
                ],
                'hi'       => [
                    'content' => '<p><strong>This is testing terms & conditions.</strong></p>'
                ],
            ],
            [
                'type' => 'policy',
                'en'       => [
                    'content' => '<p><strong>This is testing privacy policy.</strong></p>',
                ],
                'hi'       => [
                    'content' => '<p><strong>This is testing privacy policy.</strong></p>',
                ],
            ],
            [
                'type' => 'cancellation_policy',
                'en'       => [
                    'content' => '<p><strong>This is testing cancellation policy.</strong></p>',
                ],
                'hi'       => [
                    'content' => '<p><strong>This is testing cancellation policy.</strong></p>',
                ],
            ],
        ];
        foreach ($policyData as $data){
            Policy::create($data);
        }
    }
}
