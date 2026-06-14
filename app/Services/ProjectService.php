<?php

namespace App\Services;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class ProjectService
{
    public function __construct(public ProjectRepository $projectRepository)
    {}

    public function getProjects(int $userId, int $perPage): LengthAwarePaginator
    {
        return $this->projectRepository->getProjectsByUserId($userId, $perPage);
    }

    public function createProject(array $data): void
    {
        $this->projectRepository->createProject($data);
    }

    public function updateProject(int $userId, Project $project, array $data): void
    {
        abort_if(!$this->projectRepository
            ->hasProjectAccess($project->id, [$userId], ProjectRole::OWNER), 403);
        $project->update($data);
    }

    public function deleteProject(int $userId, Project $project): void
    {
        abort_if(!$this->projectRepository
            ->hasProjectAccess($project->id, [$userId], ProjectRole::OWNER), 403);
        $project->delete();
    }
}
