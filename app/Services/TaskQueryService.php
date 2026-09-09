<?php

namespace App\Services;

use Illuminate\Http\Request;

class TaskQueryService
{
    public function apply(Request $request)
    {
        $query = $request->user()
            ->tasks()
            ->with('project');

        if ($request->filled('search')) {
            $search = trim($request->query('search'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', (int) $request->query('project_id'))
                  ->whereHas('project', function ($q) use ($request) {
                      $q->where('user_id', $request->user()->id);
                });
        }

        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->query('priority'));
        }

        if ($request->filled('due_date_from')) {
            $query->whereDate('due_date', '>=', $request->query('due_date_from'));
        }

        if ($request->filled('due_date_to')) {
            $query->whereDate('due_date', '<=', $request->query('due_date_to'));
        }

        if ($request->has('sort')) {
            $allowedSortFields = ['id', 'name', 'status', 'priority', 'due_date', 'created_at', 'updated_at'];
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