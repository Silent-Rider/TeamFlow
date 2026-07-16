<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Task $task): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Task $task): bool
    {
        return $user->role === UserRole::ADMIN || $task->creator_id === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->role === UserRole::ADMIN || $task->creator_id === $user->id;
    }

    public function toggle(User $user, Task $task): bool
    {
        return $user->role === UserRole::ADMIN
            || $task->creator_id === $user->id
            || $task->assignee_id === $user->id;
    }
}
