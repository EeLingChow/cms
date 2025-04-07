<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin')->insert([
            [
                'id' => 1,
                'profile_id' => 1,
                'username' => 'admin',
                'password' => bcrypt('123qwe'),
                'fullname' => 'Admin',
                'is_superadmin' => true,
            ],
        ]);
    }
}
