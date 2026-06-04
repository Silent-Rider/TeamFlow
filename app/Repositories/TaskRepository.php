<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskRepository
{
    public function getTasks(int $userId, string $filter, int $perPage): LengthAwarePaginator
    {
        $query = Task::query()
            ->where('assignee_id', $userId)
            ->orderBy('due_date');

        if ($filter === 'active') {
            $query->where('is_done', false);
        } elseif ($filter === 'done') {
            $query->where('is_done', true);
        }
        return $query->paginate($perPage);
    }
}
