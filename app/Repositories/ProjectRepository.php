<?php

namespace App\Repositories;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\ProjectUser;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class ProjectRepository
{
    public function getProjectsByUserId(int $userId, int $perPage): LengthAwarePaginator
    {
        return Project::query()
            ->whereHas('users', fn ($query) => $query->where('users.id', $userId))
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function hasProjectAccess(int $projectId, array $userIds, ProjectRole $accessLevel = null): bool
    {
        $query = ProjectUser::query()
            ->where('project_id', $projectId)
            ->whereIn('user_id', $userIds);

        if ($accessLevel) {
            $query->where('access_level', $accessLevel);
        }
        return $query->count() === count($userIds);
    }

    public function createProject(array $data): void
    {
        Project::create($data);
    }
}
