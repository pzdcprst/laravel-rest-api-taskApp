<?php

namespace App\Services\Filters\ProjectFilters;

use App\Services\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProjectSearchFilter extends BaseFilter
{
    protected $filters = [
        'name',
    ];

    public function apply(Builder $query, Request $request): Builder
    {
        $name = $this->getStringFilterValue($request, 'name');

        if ($name === null || $name === '') {
            return $query;
        }

        return $query->where('name', 'like', "%{$name}%");
    }
}
