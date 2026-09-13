<?php

namespace App\Services\Filters\TaskFilters;

use App\Services\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TaskDateRangeFilter extends BaseFilter
{
    protected function getFilters(): array
    {
        return ['due_date_from', 'due_date_to'];
    }

    protected function filterDueDateFrom(Builder $query, Request $request): Builder
    {
        $value = $this->getStringFilterValue($request, 'due_date_from');

        if ($value === null || $value === '') {
            return $query;
        }

        return $query->whereDate('due_date', '>=', $value);
    }

    protected function filterDueDateTo(Builder $query, Request $request): Builder
    {
        $value = $this->getStringFilterValue($request, 'due_date_to');

        if ($value === null || $value === '') {
            return $query;
        }

        return $query->whereDate('due_date', '<=', $value);
    }
}
