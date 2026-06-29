<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

readonly class UserRepository
{
    private const int TTL = 600;
    public function getAllUsersData(int $userId, int $page, int $perPage): array
    {
        $key = "users:list:exclude:{$userId}:page:{$page}:per:{$perPage}";

        return Cache::tags(['users'])->remember($key, self::TTL, function () use ($userId, $page, $perPage) {
            $query = User::query()
                ->where('id', '!=', $userId)
                ->orderBy('name');

            return [
                'total' => $query->count(),
                'items' => $query->forPage($page, $perPage)->get()->toArray(),
            ];
        });
    }
}
