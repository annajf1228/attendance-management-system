<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonPeriod;

class UserRepository extends BaseRepository
{
    /**
     * @return Illuminate\Database\Eloquent\Builder
     */
    function createQuery(): Builder
    {
        return User::query();
    }

    /**
     * ID検索取得(対象の期間)
     * 
     * @param int $id
     * @param \Carbon\CarbonPeriod $targetMonthPeriods
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFailWithWorkRecord(int $id, CarbonPeriod $targetMonthPeriods): Model
    {
        $query = $this->createQuery()->with([
            'workRecords' => function ($query) use ($targetMonthPeriods) {
                $query->whereBetween('work_date', [$targetMonthPeriods->startDate, $targetMonthPeriods->endDate]);
            }
        ]);
        return $query->findOrFail($id);
    }
}
