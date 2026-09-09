<?php

namespace App\Services\Filters\TaskFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Services\Filters\EqualityFilter;

class TaskPriorityFilter extends EqualityFilter
{
    protected $filters = [
        'priority',
    ];

    protected function getColumn(): string
    {
        return 'priority';
    }

    protected function getRequestKey(): string
    {
        return 'priority';
    }

    public function apply(Builder $query, Request $request): Builder
    {
        return $this->filterByValue($query, $request);
    }
}