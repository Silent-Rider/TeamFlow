<?php

namespace App\Observers;

use App\Models\Task;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskStatusChangedNotification;
use App\Notifications\TaskUpdatedNotification;

class TaskObserver
{
    public function created(Task $task): void
    {
        if ($task->assignee_id) {
            $task->assignee->notify(new TaskAssignedNotification($task));
        }
    }

    public function updated(Task $task): void
    {
        if ($task->wasChanged('assignee_id') && $task->assignee_id) {
            $task->assignee->notify(new TaskAssignedNotification($task));
        }

        if ($task->wasChanged('is_done')) {
            $userId = auth()->id();
            if ($userId == $task->assignee_id) {
                $task->creator->notify(new TaskStatusChangedNotification($task, 'creator'));
            } else if ($userId == $task->creator_id) {
                $task->assignee->notify(new TaskStatusChangedNotification($task, 'assignee'));
            }
        }

        if ($task->wasChanged(['name', 'priority', 'description', 'due_date'])) {
            $task->assignee->notify(new TaskUpdatedNotification($task));
        }
    }
}
