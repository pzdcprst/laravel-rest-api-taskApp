<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class EqualityFilter extends BaseFilter
{
    abstract protected function getColumn(): string;

    abstract protected function getRequestKey(): string;

    protected function filterByValue(Builder $query, Request $request): Builder
    {
        $value = $this->getStringFilterValue($request, $this->getRequestKey());

        if ($value === null || $value === '') {
            return $query;
        }

        return $query->where($this->getColumn(), $value);
    }
}
