<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('cache:warm')]
#[Description('Warming up the cache of user and project lists')]
class WarmCacheCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ProjectRepository $projectRepository,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $userIds = User::query()->pluck('id');
        $this->withProgressBar($userIds, function (int $userId) {
            $this->userRepository->getAllUsersData($userId, page: 1, perPage: 50);
            $this->projectRepository->getProjectsDataByUserId($userId, page: 1, perPage: 20);
        });
        $this->newLine();
        $this->info('Кэш прогрет для ' . $userIds->count() . ' пользователей.');

        return self::SUCCESS;
    }
}
