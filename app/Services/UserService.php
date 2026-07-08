<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;

readonly class UserService
{
    public function __construct(public UserRepository $userRepository)
    {}
    public function getUsersData(User $user, int $page, int $perPage): array
    {
        $usersData = $this->userRepository->getAllUsersData($user, $page, $perPage);
        $usersData['items'] = $usersData['items'] ? User::hydrate($usersData['items']) : Collection::empty();
        return $usersData;
    }
}
