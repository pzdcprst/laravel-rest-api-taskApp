<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateDueDateRequest;
use App\Http\Requests\UpdateStatusRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\V1\Collections\TaskCollection;
use App\Http\Resources\V1\TaskResource;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskDueDateService;
use App\Services\TaskQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Services\TaskStatusService;
use Carbon\Carbon;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly TaskStatusService $taskStatusService,
        private readonly TaskDueDateService $taskDueDateService,
    )
    {
        $this->authorizeResource(Task::class, 'task');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, TaskQueryService $queryService)
    {
        $tasks = $queryService->apply($request);

        return new TaskCollection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request, Project $project)
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;

        $task = $project->tasks()->create($validated);

        return new TaskResource($task->load('project'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully.',
        ]);
    }

    public function updateStatus(UpdateStatusRequest $request, Task $task)
    {
        $this->taskStatusService->changeStatus(
            $task,
            $request->validated()['status'],
        );

        return response()->json([
            'message' => 'Task status updated successfully.',
        ]);
    }

    public function setDueDate(UpdateDueDateRequest $request, Task $task)
    {
        $dueDate = Carbon::parse($request->validated()['due_date']);
    
        $this->taskDueDateService->updateDueDate($task, $dueDate);

        return response()->json([
            'message' => 'Task due date updated successfully.',
        ]);
    }
}
