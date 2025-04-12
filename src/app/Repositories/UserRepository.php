<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
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

    /**
     * 検索条件に合致するペジネーションを取得
     * 
     * @param int $perPage
     * @param array $sortData [column => true|false] 昇順の場合はtrue、降順の場合はfalseを指定
     * @param array $conditions [column => value] 検索条件を指定
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function searchWithPaginate(int $perPage = 20, array $sortData = [], array $conditions = []): LengthAwarePaginator
    {
        $query = $this->createQuery()->with(['workRecords']);

        // 検索条件
        foreach ($conditions as $column => $value) {
            if($value) {
                if($column === 'employee_number') {
                    $query->where($column, $value);
                }
                if($column === 'name') {
                    $query->where($column, 'like', '%' . $value . '%');
                }
                if ($column === 'work_date') {
                    $query->whereHas('workRecords', function ($q) use ($value) {
                        $q->where('work_date', $value);
                    });
                } 
            }
        }

        // ソート
        if (!empty($sortData)) {
            foreach ($sortData as $column => $type) {
                $sortType = $type ? 'asc' : 'desc';
                $query->orderBy($column, $sortType);
            }
        }
        return $query->paginate($perPage);
    }
}
