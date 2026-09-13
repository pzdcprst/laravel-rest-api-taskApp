<?php

namespace App\Services\Filters\TaskFilters;

use App\Services\Filters\EqualityFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TaskProjectFilter extends EqualityFilter
{
    protected $filters = [
        'project_id',
    ];

    protected function getColumn(): string
    {
        return 'project_id';
    }

    protected function getRequestKey(): string
    {
        return 'project_id';
    }

    public function apply(Builder $query, Request $request): Builder
    {
        return $this->filterByValue($query, $request);
    }
}
