<?php

namespace App\Repositories;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\ProjectUser;
use Illuminate\Support\Facades\Cache;

readonly class ProjectRepository
{
    private const int TTL = 5 * 60 * 60;
    public function getProjectsDataByUserId(int $userId, int $page, int $perPage): array
    {
        $key = "projects:user:{$userId}:page:{$page}:per:{$perPage}";
        return Cache::tags(['projects'])->remember($key, self::TTL, function () use ($userId, $page, $perPage) {
            $query = Project::query()
                ->whereHas('users', fn ($query) => $query->where('users.id', $userId))
                ->orderBy('name');
            return [
                'total' => $query->count(),
                'items' => $query->forPage($page, $perPage)->get()->toArray(),
            ];
        });
    }

    public function hasProjectAccess(int $projectId, array $userIds, ?ProjectRole $accessLevel = null): bool
    {
        $userIds = array_unique($userIds);
        $key = "project_access:{$projectId}:" . implode(',', $userIds) . ':' . ($accessLevel?->value ?? 'any');

        return Cache::tags(['project_access'])->remember($key, self::TTL, function () use ($projectId, $userIds, $accessLevel) {
            $query = ProjectUser::query()
                ->where('project_id', $projectId)
                ->whereIn('user_id', $userIds);

            if ($accessLevel) {
                $query->where('role', $accessLevel);
            }
            return $query->count() === count($userIds);
        });
    }

    public function createProject(array $data): void
    {
        $pivotData = [
            'role' => ProjectRole::OWNER,
            'created_at' => now()
        ];

        $project = Project::create($data);
        $project->users()->attach($project->creator_id, $pivotData);

        Cache::tags(['projects', 'project_access'])->flush();
    }
}
