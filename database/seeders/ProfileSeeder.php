<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profile')->insert([
            'id' => 1,
            'name' => 'All Permission',
            'is_superadmin' => true,
        ]);

        $modules = DB::table('module')->get();

        $data = [];
        foreach ($modules as $m) {
            $data[] = [
                'profile_id' => 1,
                'module_id' => $m->id,
                'permission' => 15,
            ];
        }

        DB::table('profile_module_assignment')->insert($data);
    }
}
