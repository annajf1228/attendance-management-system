<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\WorkRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WorkRecordRepository extends BaseRepository
{
    /**
     * @return Illuminate\Database\Eloquent\Builder
     */
    function createQuery(): Builder
    {
        return WorkRecord::query();
    }

    /**
     * @param int $userId
     * @param Carbon\Carbon $today 2025-03-22形式
     * @return Illuminate\Database\Eloquent\Model|null
     */
    function findByUserAndDate(int $userId, Carbon $today): ?Model
    {
        return $this->createQuery()->where('user_id', $userId)
            ->where('work_date', $today)
            ->first();
    }

    /**
     * 指定したユーザーの最古または最新の勤務日を取得
     * 
     * @param int $userId
     * @param bool $isMin
     * @return Carbon\Carbon|null
     */
    function getWorkDateByUser(int $userId, bool $isMin = true): ?Carbon
    {
        $date = $this->createQuery()->where('user_id', $userId)
            ->{$isMin ? 'min' : 'max'}('work_date');
        return $date ? Carbon::parse($date) : null;
    }
}
