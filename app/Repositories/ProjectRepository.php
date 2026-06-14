<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\ProjectUser;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectRepository
{
    public function getProjectsByUserId(int $userId, int $perPage): LengthAwarePaginator
    {
        return Project::query()
            ->whereHas('users', fn ($query) => $query->where('users.id', $userId))
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function hasProjectAccess(int $projectId, array $userIds): bool
    {
        $count = ProjectUser::query()
            ->where('project_id', $projectId)
            ->whereIn('user_id', $userIds)
            ->count();

        return $count === count($userIds);
    }
}
