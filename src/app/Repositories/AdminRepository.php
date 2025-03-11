<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Builder;


class AdminRepository extends BaseRepository
{
    /**
     * @return Illuminate\Database\Eloquent\Builder
     */
    function createQuery(): Builder
    {
        return Admin::query();
    }
    
}
