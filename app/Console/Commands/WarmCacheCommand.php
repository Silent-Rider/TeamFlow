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
        $users = User::query()->get();
        $this->withProgressBar($users, function (User $user) {
            $this->userRepository->getUsersDataByCompanyId($user, page: 1, perPage: 50);
            $this->projectRepository->getProjectsDataByUserId($user->id, page: 1, perPage: 20);
        });
        $this->newLine();
        $this->info('Кэш прогрет для ' . $users->count() . ' пользователей.');

        return self::SUCCESS;
    }
}
