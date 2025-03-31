<?php

namespace App\Libraries;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

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
}
