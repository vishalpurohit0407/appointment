<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HtlTableJsonData;

class HtlTableJsonDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$htlTableJsonData=[
            [
                'table_name' => 'htl_cust_info',
                'file_url' => 'htl-json-files/cust_info_27_Jul_2021_10_34_01.json',
                'status' => '1',

            ],
            [
                'table_name' => 'htl_cust_latest_trans',
                'file_url' => 'htl-json-files/cust_latest_trans_27_Jul_2021_10_34_01.json',
                'status' => '1',

            ],
            [
                'table_name' => 'htl_cust_part_summaries',
                'file_url' => 'htl-json-files/part_summary_27_Jul_2021_10_34_36.json',
                'status' => '1',

            ],
            [
                'table_name' => 'htl_cust_price_books',
                'file_url' => 'htl-json-files/price_book_27_Jul_2021_10_34_47.json',
                'status' => '1',

            ],
            [
                'table_name' => 'htl_cust_summaries',
                'file_url' => 'htl-json-files/cust_summary_27_Jul_2021_10_35_06.json',
                'status' => '1',

            ],
            [
                'table_name' => 'htl_ex_rates',
                'file_url' => 'htl-json-files/ex_rate_27_Jul_2021_10_35_07.json',
                'status' => '1',

            ],
            [
                'table_name' => 'htl_part_costs',
                'file_url' => 'htl-json-files/part_cost_27_Jul_2021_10_35_07.json',
                'status' => '1',

            ],
            [
                'table_name' => 'htl_part_images',
                'file_url' => 'htl-json-files/part_image_27_Jul_2021_10_35_07.json',
                'status' => '1',

            ],
            [
                'table_name' => 'htl_part_infos',
                'file_url' => 'htl-json-files/part_info_27_Jul_2021_10_35_20.json',
                'status' => '1',

            ]
        ];
        foreach ($htlTableJsonData as $data) {
            if (HtlTableJsonData::where('table_name', $data['table_name'])->first() !== null ) continue;
            HtlTableJsonData::create([
                'table_name' => $data['table_name'],
                'file_url' => $data['file_url'],
                'status' => '1',
            ]);
        }
    }
}
