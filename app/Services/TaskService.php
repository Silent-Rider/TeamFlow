<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
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

    public function updateTask(int $userId, Task $task, array $data): void
    {
        abort_if(!$this->taskRepository->hasTaskAccess($userId, $task), 403);
        $task->update($data);
    }

    public function toggleTask(int $userId, Task $task): void
    {
        abort_if(!$this->taskRepository->hasTaskAccess($userId, $task), 403);
        $task->update(['is_done' => !$task->is_done]);
    }

    public function deleteTask(int $userId, Task $task): void
    {
        abort_if(!$this->taskRepository->hasTaskAccess($userId, $task), 403);
        $task->delete();
    }
}
