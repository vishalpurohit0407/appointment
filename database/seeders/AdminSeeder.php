<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins=[
            [
                'name' => 'Warren Craig',
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('123456'),
                'status' => '1',

            ]
        ];
        foreach ($admins as $admin) {
            if ( Admin::where('email', $admin['email'])->first() !== null ) continue;
            Admin::create([
                'name' => 'Warren Craig',
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('123456'),
                'status' => '1',
            ]);
        }
    }
}
