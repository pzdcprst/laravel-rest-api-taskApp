<?php

namespace App\Services;

use App\Services\Filters\ProjectFilters\ProjectSearchFilter;
use App\Services\Filters\SortFilter;
use Illuminate\Http\Request;

class ProjectQueryService
{
    public function __construct(
        protected ?array $filters = null
    ) {
        $this->filters = $filters ?? [
            new ProjectSearchFilter,
            new SortFilter([
                'id',
                'name',
                'created_at',
                'updated_at',
            ]),
        ];
    }

    public function apply(Request $request)
    {
        $query = $request->user()
            ->projects()
            ->withCount('tasks')
            ->getQuery();

        foreach ($this->filters as $filter) {
            $query = $filter->apply($query, $request);
        }

        $perPage = $request->query('per_page', 15);
        $perPage = is_numeric($perPage)
            ? max(1, min((int) $perPage, 100))
            : 15;

        return $query->paginate($perPage);
    }
}
