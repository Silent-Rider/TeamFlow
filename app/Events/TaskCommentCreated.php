<?php

namespace App\Events;

use App\Models\TaskComment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCommentCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private TaskComment $taskComment;
    public function __construct(TaskComment $taskComment) {
        $this->taskComment = $taskComment;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('task.' . $this->taskComment->task_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'comment.created';
    }

    public function broadcastWith(): array
    {
        return [
            'html' => view('task.partials.task-comment-item', [
                'comment' => $this->taskComment,
            ])->render(),
        ];
    }
}
