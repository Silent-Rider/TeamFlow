<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

readonly class UserRepository
{
    private const int TTL = 600;
    public function getAllUsers(int $userId, int $page, int $perPage): LengthAwarePaginator
    {
        $key = "users:list:exclude:{$userId}:page:{$page}:per:{$perPage}";

        return Cache::tags(['users'])->remember($key, self::TTL, function () use ($userId, $page, $perPage) {
            return User::query()
                ->where('id', '!=', $userId)
                ->orderBy('name')
                ->paginate($perPage, page: $page);
        });
    }
}
