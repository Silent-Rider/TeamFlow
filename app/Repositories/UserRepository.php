<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

readonly class UserRepository
{
    public function getUsersDataByCompanyId(User $user, int $page, int $perPage): array
    {
        $userId = $user->id;
        $companyId = $user->company_id;

        $key = "users:company:exclude:{$userId}:page:{$page}:per:{$perPage}";

        return Cache::tags(['users'])->remember($key, config('cache.ttl.users'),
            function () use ($userId, $companyId, $page, $perPage) {
                $query = User::query()
                    ->where('company_id', $companyId)
                    ->where('id', '!=', $userId)
                    ->orderBy('name');

                return [
                    'total' => $query->count(),
                    'items' => $query->forPage($page, $perPage)->get()->toArray(),
                ];
            }
        );
    }

    public function getUsersDataByProjectId(int $projectId, int $page, int $perPage): array
    {
        $key = "users:project:{$projectId}:page:{$page}:per:{$perPage}";

        return Cache::tags(['users'])->remember($key, config('cache.ttl.users'),
            function () use ($projectId, $page, $perPage) {
                $query = Project::find($projectId)
                    ->users();

                return [
                    'total' => $query->count(),
                    'items' => $query->forPage($page, $perPage)->get()->toArray(),
                ];
            }
        );
    }
}
