<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            ['state_name' => 'Andhra Pradesh', 'state_code' => 'AP', 'country_id' => '1'],
            ['state_name' => 'Arunachal Pradesh', 'state_code' => 'AR', 'country_id' => '1'],
            ['state_name' => 'Assam', 'state_code' => 'AS', 'country_id' => '1'],
            ['state_name' => 'Bihar', 'state_code' => 'BR', 'country_id' => '1'],
            ['state_name' => 'Chhattisgarh', 'state_code' => 'CG', 'country_id' => '1'],
            ['state_name' => 'Goa', 'state_code' => 'GA', 'country_id' => '1'],
            ['state_name' => 'Gujarat', 'state_code' => 'GJ', 'country_id' => '1'],
            ['state_name' => 'Haryana', 'state_code' => 'HR', 'country_id' => '1'],
            ['state_name' => 'Himachal Pradesh', 'state_code' => 'HP', 'country_id' => '1'],
            ['state_name' => 'Jharkhand', 'state_code' => 'JH', 'country_id' => '1'],
            ['state_name' => 'Karnataka', 'state_code' => 'KA', 'country_id' => '1'],
            ['state_name' => 'Kerala', 'state_code' => 'KL', 'country_id' => '1'],
            ['state_name' => 'Madhya Pradesh', 'state_code' => 'MP', 'country_id' => '1'],
            ['state_name' => 'Maharashtra', 'state_code' => 'MH', 'country_id' => '1'],
            ['state_name' => 'Manipur', 'state_code' => 'MN', 'country_id' => '1'],
            ['state_name' => 'Meghalaya', 'state_code' => 'ML', 'country_id' => '1'],
            ['state_name' => 'Mizoram', 'state_code' => 'MZ', 'country_id' => '1'],
            ['state_name' => 'Nagaland', 'state_code' => 'NL', 'country_id' => '1'],
            ['state_name' => 'Odisha', 'state_code' => 'OR', 'country_id' => '1'],
            ['state_name' => 'Punjab', 'state_code' => 'PB', 'country_id' => '1'],
            ['state_name' => 'Rajasthan', 'state_code' => 'RJ', 'country_id' => '1'],
            ['state_name' => 'Sikkim', 'state_code' => 'SK', 'country_id' => '1'],
            ['state_name' => 'Tamil Nadu', 'state_code' => 'TN', 'country_id' => '1'],
            ['state_name' => 'Telangana', 'state_code' => 'TG', 'country_id' => '1'],
            ['state_name' => 'Tripura', 'state_code' => 'TR', 'country_id' => '1'],
            ['state_name' => 'Uttar Pradesh', 'state_code' => 'UP', 'country_id' => '1'],
            ['state_name' => 'Uttarakhand', 'state_code' => 'UK', 'country_id' => '1'],
            ['state_name' => 'West Bengal', 'state_code' => 'WB', 'country_id' => '1'],
            ['state_name' => 'Jammu and Kashmir', 'state_code' => 'JK', 'country_id' => '1'],
        ];


        DB::table('states')->insert($states);
    }
}
