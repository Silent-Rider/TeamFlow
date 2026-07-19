<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Task $task
    )
    {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $taskName = "\"#{$this->task->id} {$this->task->name}\"";
        return (new MailMessage)
            ->subject(__('tasks.new_task') . ": " . $taskName)
            ->line(__('tasks.you_assigned_to_task') . " " . $taskName . ".")
            ->action(__('tasks.go_to_task'), url('/tasks'))
            ->line(__('tasks.good_luck'));
    }
}
