<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskIndexRequest;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }
    public function index(TaskIndexRequest $request): View
    {
        $tasks = $this->taskRepository->getTasks(
            auth()->id(),
            $request->getFilter(),
            $request->getPerPage());
        return view('tasks', compact('tasks'));
    }

    public function toggle(Task $task): RedirectResponse
    {
        abort_if($task->assignee_id !== auth()->id(), 403);
        $task->update(['is_done' => !$task->is_done]);
        return back();
    }
}
