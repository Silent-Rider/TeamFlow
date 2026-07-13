<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\TaskCreateRequest;
use App\Http\Requests\Task\TaskIndexRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(readonly TaskService $taskService)
    {}
    public function index(TaskIndexRequest $request): View|JsonResponse
    {
        $filter = $request->getFilter();
        $perPage = $request->getPerPage();
        $projectId = $request->getProjectId();
        $tasks = $projectId
            ? $this->taskService->getProjectTasks($projectId, auth()->id(), $filter, $perPage)
            : $this->taskService->getAssigneeTasks(auth()->id(), $filter, $perPage);

        if ($request->wantsJson()) {
            return response()->json([
                'html' => view('task.partials.task-list', [
                    'tasks' => $tasks,
                    'filter' => $filter,
                    'projectId' => $projectId,
                    'embedded' => true,
                ])->render(),
            ]);
        }

        return view('task.tasks', compact('tasks'));
    }

    public function create(TaskCreateRequest $request): RedirectResponse
    {
        $this->taskService->createTask(auth()->id(), $request->getTaskData());
        return back();
    }

    public function update(TaskUpdateRequest $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);
        $this->taskService->updateTask($task, $request->validated());
        return back();
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);
        $this->taskService->deleteTask($task);
        return back();
    }

    public function toggle(Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        $this->taskService->toggleTask($task);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_done' => $task->is_done,
                'id' => $task->id
            ]);
        } else abort('404');
    }

    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task);
        $task->load(['taskComments.user', 'assignee']);

        if (request()->wantsJson()) {
            return response()->json([
                'task' => [
                    'id' => $task->id,
                    'name' => $task->name
                ],
                'html' => view('task.partials.task-details', compact('task'))->render(),
            ]);
        } else abort('404');
    }
}
