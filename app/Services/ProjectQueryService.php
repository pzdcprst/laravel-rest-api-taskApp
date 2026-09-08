<?php

namespace App\Services;

use Illuminate\Http\Request;

class ProjectQueryService
{
    public function apply(Request $request)
    {
        $query = $request->user()
            ->projects()
            ->withCount('tasks');

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->query('name') . '%');
        }

        if ($request->has('sort')) {
            $allowedSortFields = ['id', 'name', 'description', 'created_at', 'updated_at'];
            $sortField = $request->query('sort');

            if (in_array($sortField, $allowedSortFields, true)) {
                $direction = strtolower((string) $request->query('direction', 'asc'));
                $direction = in_array($direction, ['asc', 'desc'], true) ? $direction : 'asc';
                $query->orderBy($sortField, $direction);
            }
        }

        $perPage = $request->query('per_page', 15);
        $perPage = is_numeric($perPage) ? max(1, min((int) $perPage, 100)) : 15;

        return $query->paginate($perPage);
    }
}