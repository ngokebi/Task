<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::Create([
            'name' => 'Ikeaba Ngozi',
            'email' => 'kebidegozi@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('@rch_101!'), // password
            'status' => 1,

          ]);
          Admin::factory(10)->create();
    }
}
