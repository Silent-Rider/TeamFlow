<?php

namespace App\Policies;

use App\Enums\ProjectRole;
use App\Enums\UserRole;
use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Project $project): bool
    {
        return $user->role === UserRole::ADMIN
            || $project->users()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Project $project): bool
    {
        return $user->role === UserRole::ADMIN
            || $project->users()
                ->where('user_id', $user->id)
                ->wherePivot('role', ProjectRole::OWNER)
                ->exists();
    }

    public function delete(User $user, Project $project): bool
    {
        return $this->update($user, $project);
    }
}
