<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
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
        if (app()->isLocal()) {
            Admin::truncate();

            $nameList = [
                '山田　管理1',
                '山田　管理2',
                '山田　管理3',
            ];

            $dataList = [];

            foreach ($nameList as $index => $name) {
                $dataList[] = [
                    'id' => $index + 1,
                    'employee_number' => 'AD' . ($index + 1),
                    'name' => $name,
                    'password' => Hash::make('test1234'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            DB::table('admins')->insert($dataList);
        }
    }
}
