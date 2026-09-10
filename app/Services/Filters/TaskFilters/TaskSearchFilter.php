<?php

namespace App\Services\Filters\TaskFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Services\Filters\BaseFilter;

class TaskSearchFilter extends BaseFilter
{
    protected $filters = [
        'search',
    ];

    protected function filterSearch(Builder $query, Request $request): Builder
    {
        $search = $this->getStringFilterValue($request, 'search');

        if($search === null || $search === '') {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }
}