<?php
 
namespace Database\Seeders;
 
use App\Models\Policy;
use Illuminate\Database\Seeder;
 
class UpdatePolicyDataSeeder extends Seeder
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
                'type' => 'refund_policy',
                'en'       => [
                    'content' => '<p><strong>This is testing refund policy.</strong></p>',
                ],
                'hi'       => [
                    'content' => '<p><strong>This is testing refund policy.</strong></p>',
                ],
            ],
        ];
        foreach ($policyData as $data){
            Policy::create($data);
        }
    }
}