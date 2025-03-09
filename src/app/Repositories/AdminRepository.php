<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Exceptions\DataNotFoundException;
use App\Exceptions\DBException;
use App\Exceptions\NumberFormatException;
use App\Models\Admin;
use App\Repositories\Conditions\Condition;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

/**
 * 社員コード情報リポジトリ
 */
class AdminRepository extends BaseRepository
{
    /**
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function createQuery(): Builder
    {
        return Admin::query();
    }
    
}
