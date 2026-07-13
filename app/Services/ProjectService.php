<?php

namespace App\Services;

use App\Models\Project;
use App\Repositories\ProjectRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

readonly class ProjectService
{
    public function __construct(public ProjectRepository $projectRepository)
    {}

    public function getProjectsData(int $userId, int $page, int $perPage): array
    {
        $projectsData = $this->projectRepository->getProjectsDataByUserId($userId, $page, $perPage);
        $projectsData['items'] = $projectsData['items']
            ? Project::hydrate($projectsData['items'])
            : Collection::empty();
        return $projectsData;
    }

    public function createProject(array $data): void
    {
        $memberIds = collect($data['members'] ?? [])
            ->reject(fn ($id) => (int) $id === (int) $data['creator_id'])
            ->values()
            ->all();
        $projectData = Arr::except($data, ['members']);
        $this->projectRepository->createProject(projectData: $projectData, memberIds:  $memberIds);
    }

    public function updateProject(Project $project, array $data): void
    {
        $memberIds = collect($data['members'] ?? [])
            ->reject(fn ($id) => (int) $id === (int) $project->creator_id)
            ->values()
            ->all();
        $projectData = Arr::except($data, ['members']);
        $this->projectRepository->updateProject($project, projectData: $projectData, memberIds:  $memberIds);
    }

    public function deleteProject(Project $project): void
    {
        $project->delete();
        Cache::tags(['projects', 'project_access'])->flush();
    }
}
