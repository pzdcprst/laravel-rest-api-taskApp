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
use App\Services\TaskQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Enums\Status;
use App\Exceptions\TaskExceptions\InvalidTaskStatusTransitionException;
use App\Exceptions\TaskExceptions\TaskAlreadyCompletedException;
use App\Exceptions\TaskExceptions\TaskCannotBeCancelledException;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
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
        $newStatus = $request->validated()['status'];

        if ($task->status === Status::completed && $newStatus !== Status::completed->value) {
            throw new TaskAlreadyCompletedException();
        }

        if ($newStatus === Status::cancelled->value && $task->status === Status::completed) {
            throw new TaskCannotBeCancelledException();
        }

        if($newStatus === Status::pending->value && $task->status === Status::completed) {
            throw new InvalidTaskStatusTransitionException(Status::pending->value, Status::completed->value);
        }

        $task->update(['status' => $newStatus]);

        return response()->json([
            'message' => 'Task status updated successfully.',
        ]);
    }

    public function setDueDate(UpdateDueDateRequest $request, Task $task)
    {
        $task->update($request->validated());

        return response()->json([
            'message' => 'Task due date updated successfully.',
        ]);
    }
}
