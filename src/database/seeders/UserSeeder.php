<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->isLocal()) {
            DB::table('users')->delete();
            DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');

            $nameList = [
                '山田　太郎',
                '山田　花子',
                '鈴木　一郎',
            ];

            $dataList = [];

            foreach ($nameList as $index => $name) {
                $id = $index + 1;
                $dataList[] = [
                    'employee_number' => 'US' . ($id),
                    'name' => $name,
                    'email' => 'example' . ($id) . '@example.com',
                    'password' => Hash::make('test1234'),
                    'join_date' => '2025/1/' . $id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            DB::table('users')->insert($dataList);
        }
    }
}
