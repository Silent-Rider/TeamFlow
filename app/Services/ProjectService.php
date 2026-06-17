<?php

namespace App\Services;

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

    public function updateProject(Project $project, array $data): void
    {
        $project->update($data);
    }

    public function deleteProject(Project $project): void
    {
        $project->delete();
    }
}
