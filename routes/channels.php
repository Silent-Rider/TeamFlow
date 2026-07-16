<?php

use App\Models\Task;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('task.{taskId}', function ($user, $taskId) {
    $task = Task::find($taskId);
    return $task ? $user->can('view', $task) : false;
});
