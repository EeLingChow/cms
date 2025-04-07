<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $masterFields = ['id', 'master_id', 'name', 'modulekey', 'sequence', 'route', 'is_superadmin', 'is_hidden'];
        $masters = [
            [1, 1, 'Super Admin', 'superadmin', 0.00, '', true, false],
            [2, 2, 'Permissions', 'permission', 0.00, '', true, true],
            [3, 3, 'Others', 'other', 998.00, '', false, false],
            [4, 4, 'Floors', 'floors', 1.00, '', true, false],
            [5, 5, 'Categories', 'categories', 2.00, '', true, false],
            [6, 6, 'Shops', 'shops', 3.00, '', true, false],
            [7, 7, 'Reserved 1', 'reserve1', 999.99, '', true, true],
            [8, 8, 'Reserved 2', 'reserve2', 999.99, '', true, true],
            [9, 9, 'Reserved 3', 'reserve3', 999.99, '', true, true],
            [10, 10, 'Reserved 4', 'reserve4', 999.99, '', true, true],
        ];

        $modules = [
            [null, 1, 'Profiles', 'profile', 1.00, 'profiles.list', false, false],
            [null, 1, 'Modules', 'module', 1.00, 'modules.list', true, false],
            [null, 3, 'Admins', 'admin', 1.00, 'admins.list', false, false],
            [null, 1, 'Audit Logs', 'auditLog', 99.00, 'audit-logs.list', true, false],
            [null, 4, 'Floors', 'floor', 1.00, 'floors.list', false, false],
            [null, 5, 'Categories', 'category', 1.00, 'categories.list', false, false],
            [null, 6, 'Shops', 'shop', 1.00, 'shops.list', false, false],
        ];

        $masterArray = [];
        $moduleArray = [];

        foreach ($masters as $m) {
            $masterArray[] = array_merge(array_combine($masterFields, $m), ['is_master' => true]);
        }

        DB::table('module')->insert($masterArray);

        foreach ($modules as $m) {
            $moduleArray[] = array_combine($masterFields, $m);
        }

        DB::table('module')->insert($moduleArray);
    }
}
