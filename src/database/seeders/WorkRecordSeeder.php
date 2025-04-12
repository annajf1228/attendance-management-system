<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\WorkRecord;
use App\Models\User;

class WorkRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->isLocal()) {
            WorkRecord::truncate();
            $users = User::all();
            $yesterday = Carbon::yesterday();
            $originalStartDate = Carbon::create(2024, 12, 1);

            $dataList = [];
            foreach ($users as $user) {
                $userId = $user->id;
                $date = $originalStartDate->copy();

                while ($date->lte($yesterday)) {
                    if ($date->isWeekend() || $date->isHoliday()) {
                        $date->addDay();
                        continue;
                    }

                    $dataList[] = [
                        'user_id'    => $userId,
                        'work_date'  => $date->toDateString(),
                        'clock_in'   => $date->copy()->setTime(9, 0, 0),
                        'clock_out'  => $date->copy()->setTime(18, 0, 0),
                        'break_time' => 60,
                        'memo'       => null,
                        'status'     => 2,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $date->addDay();
                }
            }
            DB::table('work_records')->insert($dataList);
        }
    }
}
