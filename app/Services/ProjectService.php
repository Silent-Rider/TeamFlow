<?php

namespace App\Services;

use App\Repositories\ProjectRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectService
{
    public function __construct(public ProjectRepository $projectRepository)
    {}

    public function getProjects(int $userId, int $perPage): LengthAwarePaginator
    {
        return $this->projectRepository->getProjectsByUserId($userId, $perPage);
    }
}
