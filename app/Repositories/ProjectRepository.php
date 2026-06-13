<?php

namespace App\Repositories;

use App\Models\ProjectUser;

class ProjectRepository
{
    public function hasProjectAccess(int $projectId, array $userIds): bool
    {
        $count = ProjectUser::query()
            ->where('project_id', $projectId)
            ->whereIn('user_id', $userIds)
            ->count();

        return $count === count($userIds);
    }
}
