<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private string $path = "/";

    public function __construct(
        public Task $task,
        public string $userType
    )
    {
        if ($userType === 'creator') {
            $this->path = '/projects';
        } else if ($userType === 'assignee') {
            $this->path = '/tasks';
        }
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $taskName = "\"#{$this->task->id} {$this->task->name}\"";
        $status = $this->task->is_done ?
            "\"" . __('tasks.completed_upper') . "\"" :
            "\"" . __('tasks.uncompleted') . "\"";
        return (new MailMessage)
            ->subject(__('tasks.status_changed') . ": " . $taskName)
            ->line(__('tasks.completion_status_changed') . " " . $taskName
                . " " . __('tasks.to') . " " . $status . ".")
            ->action(__('tasks.go_to_task'), url($this->path))
            ->line(__('tasks.good_luck'));
    }
}
