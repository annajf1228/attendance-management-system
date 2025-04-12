<?php

namespace App\Libraries;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

/**
 * 共通処理クラス
 */
class Common
{
    /**
     * 指定された時間を15分単位で切り捨てる
     * 
     * @param \Carbon\Carbon|null $datetime
     * @return \Carbon\Carbon
     */
    public static function floorToTime(?Carbon $datetime): Carbon
    {
        if (!$datetime) {
            return Carbon::createFromTime(0, 0);
        }

        $minutes = floor($datetime->minute / 15) * 15;
        return $datetime->minute($minutes)->second(0);
    }

    /**
     * 指定された日付の属する月の期間（CarbonPeriod）を返す
     * 
     * @param \Carbon\Carbon $datetime 指定された日付（この日付の属する月の範囲を取得する）
     * @return \Carbon\CarbonPeriod 指定された月の開始日から終了日までの日付範囲
     */
    public static function getMonthPeriod(Carbon $datetime): CarbonPeriod
    {
        $startOfMonth = $datetime->startOfMonth()->toDateString();
        $endOfMonth = $datetime->endOfMonth()->toDateString();
        return CarbonPeriod::create($startOfMonth, $endOfMonth);
    }

    /**
     * 勤務時間を取得
     * 
     * @param \Carbon\Carbon|null $clockIn 勤務開始時間
     * @param \Carbon\Carbon|null $clockOut 勤務終了時間
     * @param string|null $breakTime 休憩時間
     * @param bool $isFormat true:'H:i' false:分
     * @return string|null|int
     */
    public static function getWorkTime(?Carbon $clockIn, ?Carbon $clockOut, ?string $breakTime, bool $isFormat = true): string|null|int
    {
        if (empty($clockIn) || empty($clockOut)) {
            return null;
        }
        $floorClockIn = self::floorToTime($clockIn);
        $floorClockOut = self::floorToTime($clockOut);
        $totalMinutes = $floorClockIn->diffInMinutes($floorClockOut) -  $breakTime;

        if ($totalMinutes < 0) {
            return null;
        }
        if ($isFormat) {
            return Carbon::createFromTime(0, 0)->addMinutes($totalMinutes)->format('H:i');
        }

        return (int)$totalMinutes;
    }

    /**
     * 分単位を H:i 形式に変換
     * 
     * @param int $minutesTime
     * @return string
     */
    public static function formatMinutesToTime(int $minutesTime): string
    {
        $hours = floor($minutesTime / 60);
        $minutes = $minutesTime % 60;
        return sprintf('%d:%02d', $hours, $minutes);
    }

    /**
     * 勤怠データを日付ごとに整理
     * 
     * @param \Illuminate\Support\Collection $workRecords
     * @return \Illuminate\Support\Collection
     */
    public static function mapByDate(Collection $workRecords): Collection
    {
        return $workRecords->mapWithKeys(function ($workRecord) {
            $strDate = $workRecord->work_date->toDateString();
            $workTime = Common::getWorkTime($workRecord->clock_in, $workRecord->clock_out, $workRecord->break_time, true);
            $workTimeMinutes = Common::getWorkTime($workRecord->clock_in, $workRecord->clock_out, $workRecord->break_time, false);

            return [
                $strDate => [
                    'work_record_id' => $workRecord->id ?? null,
                    'clock_in'  => $workRecord->clock_in ? $workRecord->clock_in->format('H:i') : null,
                    'clock_out' => $workRecord->clock_out ? $workRecord->clock_out->format('H:i') : null,
                    'break_time' => $workRecord->break_time ? Carbon::createFromTime(0, 0)->addMinutes($workRecord->break_time)->format('H:i') : null,
                    'work_time' => $workTime ?? null,
                    'memo'       => $workRecord->memo ?? null,
                    'break_time_minutes' => $workRecord->break_time ? $workRecord->break_time : null,
                    'work_time_minutes' => $workTimeMinutes,
                ]
            ];
        });
    }

    /**
     * 勤怠データの取得
     * 
     * @param \Carbon\CarbonPeriod $targetMonthPeriods
     * @param \Illuminate\Support\Collection $workRecordList
     * @return array
     */
    public static function getWorkRecordData(CarbonPeriod $targetMonthPeriods, Collection $workRecordList): array
    {
        foreach ($targetMonthPeriods as $dateTime) {
            $date = $dateTime->toDateString();
            $workRecordData[] = [
                'work_record_id' => $workRecordList[$date]['work_record_id'] ?? '',
                'date' => $dateTime->format('Y-m-d'),
                'day'         => $dateTime->day,
                'day_of_week' => $dateTime->isoFormat('ddd'),
                'clock_in'    => $workRecordList[$date]['clock_in'] ?? '',
                'clock_out'   => $workRecordList[$date]['clock_out'] ?? '',
                'break_time'  => $workRecordList[$date]['break_time'] ?? '',
                'work_time'  => $workRecordList[$date]['work_time'] ?? '',
                'memo'        => $workRecordList[$date]['memo'] ?? '',
                'is_saturday'   => $dateTime->isSaturday(),
                'is_holiday'    => $dateTime->isHoliday() || $dateTime->isSunday() ? true : false,
            ];
        }

        return $workRecordData;
    }

}
