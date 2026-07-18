<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\CompanyRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('cache:warm')]
#[Description('Warming up the cache of user, project and company lists')]
class WarmCacheCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ProjectRepository $projectRepository,
        private readonly CompanyRepository $companyRepository
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $users = User::query()
            ->where('role', UserRole::USER)
            ->get();
        $this->withProgressBar($users, function (User $user) {
            $this->userRepository->getUsersDataByCompanyId($user, page: 1, perPage: 50);
            $this->projectRepository->getProjectsDataByUserId($user->id, page: 1, perPage: 20);
        });
        $this->newLine();
        $this->info('Кэш прогрет для ' . $users->count() . ' пользователей.');

        $companies = $this->companyRepository->getCompaniesData(1, 20);
        $this->newLine();
        $this->info('Кэш прогрет для ' . count($companies['items']) . ' компаний.');

        return self::SUCCESS;
    }
}
