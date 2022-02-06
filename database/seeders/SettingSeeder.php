<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$settings=[
            [
                'key' => 'table_script_synchronization_status',
                'value' => '0',

            ],
            [
                'key' => 'custinfo_trans_Summary_script_synchronization_status',
                'value' => '0',

            ],
            [
                'key' => 'custpart_summary_pricebook_script_synchronization_status',
                'value' => '0',

            ],
            [
                'key' => 'part_script_synchronization_status',
                'value' => '0',

            ],
            [
                'key' => 'partimage_script_synchronization_status',
                'value' => '0',

            ],
            [
                'key' => 'product_all_images_size',
                'value' => '0',

            ]
        ];
        foreach ($settings as $setting) {
            
            $settingData = Setting::where('key', $setting['key'])->first();
            if ($settingData) {
                $settingData->update($setting);  
            } else {
                Setting::create($setting);
            }
        }
    }
}
