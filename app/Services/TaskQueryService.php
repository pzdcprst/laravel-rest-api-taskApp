<?php

namespace App\Services;

use App\Services\Filters\TaskFilters\TaskDateRangeFilter;
use App\Services\Filters\TaskFilters\TaskPriorityFilter;
use App\Services\Filters\TaskFilters\TaskProjectFilter;
use App\Services\Filters\TaskFilters\TaskSearchFilter;
use App\Services\Filters\TaskFilters\TaskSortFilter;
use App\Services\Filters\TaskFilters\TaskStatusFilter;
use Illuminate\Http\Request;

class TaskQueryService
{
    public function __construct(
        protected ?array $filters = null
    ) {
        $this->filters = $filters ?? [
            new TaskSearchFilter(),
            new TaskProjectFilter(),
            new TaskStatusFilter(),
            new TaskPriorityFilter(),
            new TaskDateRangeFilter(),
            new TaskSortFilter(),
        ];
    }

    public function apply(Request $request)
    {
        $query = $request->user()
            ->tasks()
            ->with('project')
            ->getQuery();

        foreach ($this->filters as $filter) {
            $query = $filter->apply($query, $request);
        }

        $perPage = $request->query('per_page', 15);
        $perPage = is_numeric($perPage)
            ? max(1, min((int) $perPage, 100))
            : 15;

        return $query->paginate($perPage);
    }
}