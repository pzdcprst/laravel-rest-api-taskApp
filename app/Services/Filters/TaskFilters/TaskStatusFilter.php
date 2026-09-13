<?php

namespace App\Services\Filters\TaskFilters;

use App\Services\Filters\EqualityFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TaskStatusFilter extends EqualityFilter
{
    protected $filters = [
        'status',
    ];

    protected function getColumn(): string
    {
        return 'status';
    }

    protected function getRequestKey(): string
    {
        return 'status';
    }

    public function apply(Builder $query, Request $request): Builder
    {
        return $this->filterByValue($query, $request);
    }
}
