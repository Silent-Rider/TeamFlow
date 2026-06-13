<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\TaskCreateRequest;
use App\Http\Requests\Task\TaskIndexRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(readonly TaskService $taskService)
    {}
    public function index(TaskIndexRequest $request): View
    {
        $filter = $request->getFilter();
        $perPage = $request->getPerPage();
        $projectId = $request->getProjectId();
        $tasks = $projectId
            ? $this->taskService->getProjectTasks($projectId, auth()->id(), $filter, $perPage)
            : $this->taskService->getAssigneeTasks(auth()->id(), $filter, $perPage);
        return view('tasks', compact('tasks'));
    }

    public function create(TaskCreateRequest $request): RedirectResponse
    {
        $this->taskService->createTask(auth()->id(), $request->getTaskData());
        return back();
    }

    public function update(TaskUpdateRequest $request, Task $task): RedirectResponse
    {
        // Пока что без Policy
        abort_if($task->assignee_id !== auth()->id(), 403);
        $this->taskService->updateTask($task, $request->validated());
        return back();
    }

    public function toggle(Task $task): RedirectResponse
    {
        abort_if($task->assignee_id !== auth()->id(), 403);
        $this->taskService->toggleTask($task);
        return back();
    }

    public function destroy(Task $task): RedirectResponse
    {
        abort_if($task->assignee_id !== auth()->id(), 403);
        $this->taskService->deleteTask($task);
        return back();
    }
}
