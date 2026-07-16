<?php

namespace App\Services;

use App\Events\TaskCommentCreated;
use App\Models\Attachment;
use App\Models\Task;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class TaskService
{
    public function __construct(public TaskRepository    $taskRepository,
                                public ProjectRepository $projectRepository)
    {}
    public function getAssigneeTasks(int $assigneeId, string $filter, int $perPage): LengthAwarePaginator
    {
        return $this->taskRepository->getTasksByAssigneeIdAndFilter($assigneeId, $filter, $perPage);
    }

    public function getProjectTasks(int $projectId, int $userId, string $filter, int $perPage): ?LengthAwarePaginator
    {
        abort_if(!$this->projectRepository->hasProjectAccess($projectId, [$userId]), 403);
        return $this->taskRepository->getTasksByProjectIdAndFilter($projectId, $filter, $perPage);
    }

    public function createTask(int $creatorId, array $data): void
    {
        $assigneeId = $data['assignee_id'];
        $projectId = $data['project_id'];
        if ($projectId) {
            abort_if(!$this->projectRepository->hasProjectAccess($projectId, [$creatorId, $assigneeId]), 403);
        }
        $this->taskRepository->createTask($data);
    }

    public function updateTask(Task $task, array $data): void
    {
        $task->update($data);
    }

    public function toggleTask(Task $task): void
    {
        $task->update(['is_done' => !$task->is_done]);
    }

    public function deleteTask(Task $task): void
    {
        $task->delete();
    }

    public function addComment(Task $task, int $userId, string $content, ?UploadedFile $file = null): void
    {
        $comment = $task->taskComments()->create([
            'user_id' => $userId,
            'content' => $content,
        ]);

        if ($file) {
            $path = $file->store('attachments/' . $task->id, 'public');

            Attachment::create([
                'task_comment_id' => $comment->id,
                'name' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
                'filepath' => $path,
            ]);
        }

        $comment->load(['user', 'attachments']);
        broadcast(new TaskCommentCreated($comment))->toOthers();
    }
}
