<?php

namespace App\Services\Filters\ProjectFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Services\Filters\BaseFilter;

class ProjectSearchFilter extends BaseFilter
{
    protected $filters = [
        'name',
    ];

    public function apply(Builder $query, Request $request) : Builder
    {
        $name = $this->getStringFilterValue($request, 'name');

        if ($name === null || $name === '') {
            return $query;
        }

        return $query->where('name', 'like', "%{$name}%");
    }
}