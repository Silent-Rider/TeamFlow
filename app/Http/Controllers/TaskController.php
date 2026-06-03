<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $query = Task::where('assignee_id', auth()->id())->orderBy('due_date');

        if ($request->filter === 'active') {
            $query->where('is_done', false);
        } elseif ($request->filter === 'done') {
            $query->where('is_done', true);
        }

        $tasks = $query->get();

        return view('tasks', compact('tasks'));
    }

    public function toggle(Task $task): RedirectResponse
    {
        abort_if($task->assignee_id !== auth()->id(), 403);

        $task->update(['is_done' => !$task->is_done]);

        return back();
    }
}
