<?php

namespace App\Services;

use App\Models\Project;
use App\Repositories\ProjectRepository;
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
        $this->projectRepository->createProject($data);
    }

    public function updateProject(Project $project, array $data): void
    {
        $this->projectRepository->updateProject($project, $data);
    }

    public function deleteProject(Project $project): void
    {
        $project->delete();
        Cache::tags(['projects', 'project_access'])->flush();
    }
}
