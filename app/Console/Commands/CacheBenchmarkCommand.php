<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\User;
use App\Repositories\CompanyRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\Cache;

#[Signature('cache:benchmark')]
#[Description('Measuring performance with and without cache')]
class CacheBenchmarkCommand extends Command
{
    public function __construct(
        private readonly CompanyRepository $companyRepo,
        private readonly UserRepository    $userRepo,
        private readonly ProjectRepository $projectRepo,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $companyRepo = $this->companyRepo;
        $userRepo = $this->userRepo;
        $projectRepo = $this->projectRepo;
        $user = User::query()->limit(5)->get()->random();

        $companiesCacheResult = Benchmark::measure([
            'Замер кэширования компаний. Без кэша (холодный запрос)' => function () use ($companyRepo) {
                Cache::tags(['companies'])->flush();
                return $companyRepo->getCompaniesData(1, 50);
            },
            'Замер кэширования компаний. С кэшем (тёплый запрос)' => function () use ($companyRepo) {
                return $companyRepo->getCompaniesData(1, 50);
            },
        ], 100);
        Cache::tags(['companies'])->flush();

        $usersCacheResult = Benchmark::measure([
            'Замер кэширования пользователей. Без кэша (холодный запрос)' => function () use ($user, $userRepo) {
                Cache::tags(['users'])->flush();
                return $userRepo->getUsersDataByCompanyId($user, 1, 50);
            },
            'Замер кэширования пользователей. С кэшем (тёплый запрос)' => function () use ($user, $userRepo) {
                return $userRepo->getUsersDataByCompanyId($user, 1, 50);
            },
        ], 100);
        Cache::tags(['users'])->flush();

        $projectsCacheResult = Benchmark::measure([
            'Замер кэширования проектов. Без кэша (холодный запрос)' => function () use ($user, $projectRepo) {
                Cache::tags(['projects'])->flush();
                return $projectRepo->getProjectsDataByUserId($user->id, 1, 50);
            },
            'Замер кэширования проектов. С кэшем (тёплый запрос)' => function () use ($user, $projectRepo) {
                return $projectRepo->getProjectsDataByUserId($user->id, 1, 50);
            },
        ], 100);
        Cache::tags(['projects'])->flush();

        $results = array_merge($companiesCacheResult, $usersCacheResult, $projectsCacheResult);

        foreach ($results as $name => $time) {
            $this->line("{$name}: " . number_format($time, 4) . " мс");
        }

        return self::SUCCESS;
    }
}
