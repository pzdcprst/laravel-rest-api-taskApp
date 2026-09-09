<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class BaseFilter implements QueryFilterInterface
{
    protected $filters = [];

    public function apply(Builder $query, Request $request): Builder
    {
        foreach ($this->filters as $filter) {
            if ($request->has($filter)) {
                $method = 'filter' . ucfirst($filter);
                if (method_exists($this, $method)) {
                    $query = $this->$method($query, $request->query($filter));
                }
            }
        }

        return $query;
    }

    protected function getFilters(): array
    {
        return $this->filters;
    }

    protected function whenFilled(Request $request, string $key, callable $callback): ?Builder
    {
        if ($request->filled($key)) {
            return $callback($request->query($key));
        }

        return null;
    }

    protected function normalizeDirection(Request $request, string $default = 'asc'): string
    {
        $direction = strtolower((string) $request->query('direction', $default));
        return in_array($direction, ['asc', 'desc'], true) ? $direction : $default;
    }

    protected function applySort(Builder $query, Request $request, array $allowedSortFields): Builder
    {
        if (!$request->has('sort')) {
            return $query;
        }

        $sortField = $request->query('sort');

        if (!in_array($sortField, $allowedSortFields, true)) {
            return $query;
        }

        $direction = $this->normalizeDirection($request);
        return $query->orderBy($sortField, $direction);
    }

    protected function getStringFilterValue(Request $request, string $key): ?string
    {
        $value = $request->query($key);

        return $value === null ? null : trim((string) $value);
    }
}