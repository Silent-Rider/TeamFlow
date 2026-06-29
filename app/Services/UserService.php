<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class UserService
{
    public function __construct(public UserRepository $userRepository)
    {}
    public function getUsers(int $userId, int $page, int $perPage): LengthAwarePaginator
    {
        return $this->userRepository->getAllUsers($userId, $page, $perPage);
    }
}
