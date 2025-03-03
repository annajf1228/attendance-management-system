<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

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
            User::query()->delete(); // truncateの代わりにdeleteを使用
            DB::statement("ALTER TABLE users AUTO_INCREMENT = 1"); // IDをリセットする場合

            $nameList = [
                '山田　太郎',
                '山田　花子',
                '鈴木　一郎',
            ];
            
            $dataList = [];
            
            foreach ($nameList as $index => $name) {
                $dataList[] = [
                    'id' => $index + 1,
                    'employee_number' => 'US' . ($index + 1),
                    'name' => $name,
                    'email' => 'example' . ($index + 1) . '@example.com',
                    'password' => Hash::make('password'),
                    'join_date' => '2025/1/' . $index + 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
            
            DB::table('users')->insert($dataList);
        }
    }
}
