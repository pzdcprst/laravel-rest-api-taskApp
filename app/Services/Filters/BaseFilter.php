<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class BaseFilter implements QueryFilterInterface
{
    protected $filters = [];

    public function apply(Builder $query, Request $request): Builder
    {
        foreach ($this->getFilters() as $filter) {
            if (! $request->has($filter)) {
                continue;
            }

            $method = 'filter'.Str::studly($filter);

            if (! method_exists($this, $method)) {
                continue;
            }

            $query = $this->$method($query, $request);
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
        if (! $request->has('sort')) {
            return $query;
        }

        $sortField = $request->query('sort');

        if (! in_array($sortField, $allowedSortFields, true)) {
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
