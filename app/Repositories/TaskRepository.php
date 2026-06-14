<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class TaskRepository
{
    public function getTasksByAssigneeIdAndFilter(int $assigneeId, string $filter, int $perPage): LengthAwarePaginator
    {
        return $this->getTasksByForeignKeyAndFilter('assignee_id', $assigneeId, $filter, $perPage);
    }

    public function getTasksByProjectIdAndFilter(int $projectId, string $filter, int $perPage): LengthAwarePaginator
    {
        return $this->getTasksByForeignKeyAndFilter('project_id', $projectId, $filter, $perPage);
    }

    public function createTask(array $data): void
    {
        Task::create($data);
    }

    private function getTasksByForeignKeyAndFilter(string $foreignKeyName,
                                                   int $foreignKey,
                                                   string $filter,
                                                   int $perPage): LengthAwarePaginator
    {
        $query = Task::query()
            ->where($foreignKeyName, $foreignKey)
            ->orderBy('due_date');

        if ($filter === 'active') {
            $query->where('is_done', false);
        } elseif ($filter === 'done') {
            $query->where('is_done', true);
        }
        return $query->paginate($perPage);
    }
}
