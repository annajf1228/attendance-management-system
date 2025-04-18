<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Base リポジトリ
 */
abstract class BaseRepository
{
    /**
     * @return Illuminate\Database\Eloquent\Builder
     */
    abstract function createQuery(): Builder;


    /**
     * ID検索取得
     * 
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail(int $id): Model
    {
        $query = $this->createQuery();
        return $query->findOrFail($id);
    }

    /**
     * 全件取得
     * 
     * @return \Illuminate\Support\Collection
     */
    public function findAll(): Collection
    {
        $query = $this->createQuery();
        return $query->get();
    }

    /**
     * 登録
     * 
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(array $data): Model
    {
        $model = $this->createQuery()->create($data);
        return $model;
    }

    /**
     * ペジネーションを取得
     * 
     * @param int $perPage
     * @param array $sortData [column => true|false] 昇順の場合はtrue、降順の場合はfalseを指定
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 20, array $sortData = []): LengthAwarePaginator
    {
        $query = $this->createQuery();

        if (!empty($sortData)) {
            foreach ($sortData as $column => $type) {
                $sortType = $type ? 'asc' : 'desc';
                $query->orderBy($column, $sortType);
            }
        }
        return $query->paginate($perPage);
    }

    /**
     * 更新
     * 
     * @param int $id
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(int $id, array $data): Model
    {
        $model = $this->findOrFail($id);
        $model->fill($data)->save();
        return $model;
    }

    /**
     * 削除
     * 
     * @param int $id
     * @return bool 
     */
    public function delete(int $id): bool
    {
        $model = $this->findOrFail($id);
        return $model->delete();
    }

    /**
     * 共通の where 条件
     * 
     * @param array $conditions ['column' => 'value']
     * @return \Illuminate\Support\Collection
     */
    public function where(array $conditions): Collection
    {
        $query = $this->createQuery();

        foreach ($conditions as $column => $value) {
            if (isset($value)) {
                $query->where($column, $value);
            }
        }

        return $query->get();
    }
}
