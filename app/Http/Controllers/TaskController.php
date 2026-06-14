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
        $this->taskService->updateTask(auth()->id(), $task, $request->validated());
        return back();
    }

    public function toggle(Task $task): RedirectResponse
    {
        $this->taskService->toggleTask(auth()->id(), $task);
        return back();
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->taskService->deleteTask(auth()->id(), $task);
        return back();
    }
}
