<?php

namespace App\Services;

use App\Enums\ProjectRole;
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
        $project->update($data);
        Cache::tags(['projects'])->flush();
    }

    public function deleteProject(Project $project): void
    {
        $project->delete();
        Cache::tags(['projects', 'project_access'])->flush();
    }

    public function addMember(Project $project, int $userId, ProjectRole $role): void
    {
        $this->projectRepository->addMember($project, $userId, $role);
    }

    public function removeMember(Project $project, int $userId): void
    {
        $this->projectRepository->removeMember($project, $userId);
    }

    public function updateMemberRole(Project $project, int $userId, ProjectRole $role): void
    {
        $this->projectRepository->updateMemberRole($project, $userId, $role);
    }
}
