<?php

namespace App\Services\Filters\TaskFilters;

use App\Services\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TaskSortFilter extends BaseFilter
{
    protected $filters = ['sort'];

    protected function filterSort(Builder $query, Request $request): Builder
    {
        $allowedSortFields = ['id', 'name', 'status', 'priority', 'due_date', 'created_at', 'updated_at'];
        $sortField = $this->getStringFilterValue($request, 'sort');

        if ($sortField === null || $sortField === '' || ! in_array($sortField, $allowedSortFields, true)) {
            return $query;
        }

        $direction = $this->normalizeDirection($request);

        return $query->orderBy($sortField, $direction);
    }
}
