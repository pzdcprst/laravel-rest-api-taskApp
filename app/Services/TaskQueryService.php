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
        protected TaskSearchFilter $taskSearchFilter,
        protected TaskProjectFilter $taskProjectFilter,
        protected TaskStatusFilter $taskStatusFilter,
        protected TaskPriorityFilter $taskPriorityFilter,
        protected TaskDateRangeFilter $taskDateRangeFilter,
        protected TaskSortFilter $taskSortFilter
    ) {}

    public function apply(Request $request)
    {
        $query = $request->user()
            ->tasks()
            ->with('project');

        $query = $this->taskSearchFilter->apply($query, $request);
        $query = $this->taskProjectFilter->apply($query, $request);
        $query = $this->taskStatusFilter->apply($query, $request);
        $query = $this->taskPriorityFilter->apply($query, $request);
        $query = $this->taskDateRangeFilter->apply($query, $request);
        $query = $this->taskSortFilter->apply($query, $request);

        $perPage = $request->query('per_page', 15);
        $perPage = is_numeric($perPage)
            ? max(1, min((int) $perPage, 100))
            : 15;

        return $query->paginate($perPage);
    }
}