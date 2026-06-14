<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class UserRepository
{
    public function getAllUsers(int $userId, int $perPage): LengthAwarePaginator
    {
        return User::query()
            ->where('id', '!=', $userId)
            ->orderBy('name')
            ->paginate($perPage);
    }
}
