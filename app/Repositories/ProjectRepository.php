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
            $query->where('role', $accessLevel);
        }
        return $query->count() === count($userIds);
    }

    public function createProject(array $data): void
    {
        $pivotData = [
            'role' => ProjectRole::OWNER,
            'created_at' => now()
        ];

        $project = Project::create($data);
        $project->users()->attach($project->creator_id, $pivotData);
    }
}
