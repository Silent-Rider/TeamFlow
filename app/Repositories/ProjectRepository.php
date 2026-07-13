<?php

namespace App\Repositories;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\ProjectUser;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

readonly class ProjectRepository
{
    public function getProjectsDataByUserId(int $userId, int $page, int $perPage): array
    {
        $key = "projects:user:{$userId}:page:{$page}:per:{$perPage}";
        return Cache::tags(['projects'])->remember($key, config('cache.ttl.projects'), function () use ($userId, $page, $perPage) {
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
        sort($userIds);
        $key = "project_access:{$projectId}:" . implode(',', $userIds) . ':' . ($accessLevel?->value ?? 'any');

        return Cache::tags(['project_access'])->remember($key, config(('cache.ttl.project_access')), function () use ($projectId, $userIds, $accessLevel) {
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
        $memberIds = collect($data['members'] ?? [])
            ->reject(fn ($id) => (int) $id === (int) $data['creator_id'])
            ->values()
            ->all();
        $projectData = Arr::except($data, ['members']);

        DB::transaction(function () use ($projectData, $memberIds) {
            $pivotData = [
                'role' => ProjectRole::OWNER,
                'created_at' => now()
            ];

            $project = Project::create($projectData);
            $project->users()->attach($project->creator_id, $pivotData);
            $this->updateMembersList($project, $memberIds);
        });
        Cache::tags(['projects', 'project_access'])->flush();
    }

    public function updateProject(Project $project, array $data): void
    {
        $memberIds = collect($data['members'] ?? [])
            ->reject(fn ($id) => (int) $id === (int) $project->creator_id)
            ->values()
            ->all();
        $projectData = Arr::except($data, ['members']);

        DB::transaction(function () use ($project, $projectData, $memberIds) {
            $project->update($projectData);
            $this->updateMembersList($project, $memberIds);
        });
        Cache::tags(['projects', 'project_access'])->flush();
    }

    private function updateMembersList(Project $project, array $userIds): void
    {
        $project->users()->wherePivot('role', ProjectRole::MEMBER)->detach();
        $project->users()->attach($userIds, [
            'role' => ProjectRole::MEMBER,
            'created_at' => now(),
        ]);
    }
}
