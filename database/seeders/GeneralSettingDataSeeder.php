<?php

namespace Database\Seeders;
use App\Models\GeneralSetting;

use Illuminate\Database\Seeder;

class GeneralSettingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $general_settings_data = array(
            array('fb_link', 'https://www.facebook.com/yaduz_fashion_fb'),
            array('insta_link', 'https://www.instagram.com/yaduz_fashion_ig'),
            array('twitter_link', 'https://www.twitter.com/yaduz_fashion_tweeter'),
            array('system_email', 'info@mypcot.com'),
            array('system_name', 'FITNESS STUDIO'),
            array('system_contact_no', '+91 - 0000000000'),
            array('android_version', '["1.0","1.1","1.2","1.3"]'),
            array('ios_version', '["1.0","1.1","1.2","1.3"]'),
            array('android_url', 'https://www.flipkart.com/vendor'),
            array('ios_url', 'https://www.flipkart.com/vendor'),
            array('address', 'Andheri(East), Mumbai'),
            array('latitude', '19.1179° N'),
            array('longitude', '72.8631° E')
        );
        $general_settings = array();
        foreach($general_settings_data as $val){
            array_push($general_settings, array(
                            'type' => $val[0],
                            'value' => $val[1],
                            )
                        );
            }

        foreach($general_settings as $data){
            GeneralSetting::firstOrCreate($data);
        }
    }
}
