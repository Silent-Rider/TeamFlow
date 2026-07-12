<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;

readonly class UserService
{
    public function __construct(public UserRepository $userRepository)
    {}
    public function getUsersData(User $user, int $page, int $perPage, ?int $projectId = null): array
    {
        if (!$projectId) {
            $usersData = $this->userRepository->getUsersDataByCompanyId($user, $page, $perPage);
        } else {
            $usersData = $this->userRepository->getUsersDataByProjectId($projectId, $page, $perPage);
        }
        $usersData['items'] = $usersData['items'] ? User::hydrate($usersData['items']) : Collection::empty();
        return $usersData;
    }
}
