<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SortFilter extends BaseFilter
{
    protected $filters = ['sort'];

    protected $allowedSortFields = [];

    public function __construct(array $allowedSortFields)
    {
        $this->allowedSortFields = $allowedSortFields;
    }

    protected function filterSort(Builder $query, Request $request): Builder
    {
        $sortField = $this->getStringFilterValue($request, 'sort');

        if ($sortField === null || $sortField === '' || ! in_array($sortField, $this->allowedSortFields, true)) {
            return $query;
        }

        $direction = $this->normalizeDirection($request);

        return $query->orderBy($sortField, $direction);
    }
}
