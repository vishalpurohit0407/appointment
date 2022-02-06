<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(20)->create();
        // \App\Models\Patient::factory(5)->create();
        $this->call(AdminSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(HtlTableJsonDataSeeder::class);

    }
}
