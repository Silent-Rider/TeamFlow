<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Repositories\CompanyRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\Cache;

#[Signature(
    'cache:benchmark
    {iterations=100 : Number of iterations}
    {--companies : Benchmark companies only}
    {--users : Benchmark users only}
    {--projects : Benchmark projects only}'
)]
#[Description('Measuring performance with and without cache for specific entities')]
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
        $iterations = (int) $this->argument('iterations');
        $isCompanies = $this->option('companies');
        $isUsers = $this->option('users');
        $isProjects = $this->option('projects');

        if (!$isCompanies && !$isUsers && !$isProjects) {
            $isCompanies = true;
            $isUsers = true;
            $isProjects = true;
        }
        $user = null;
        if ($isUsers || $isProjects) {
            $users = User::query()->limit(5)->get();

            if ($users->isEmpty()) {
                $this->error('Ошибка: В базе данных отсутствуют пользователи.');
                $this->line('Пожалуйста, выполните регистрацию или запустите сидеры перед запуском бенчмарка.');
                return self::FAILURE;
            }

            $user = $users->random();
        }

        $companiesCacheResult = $isCompanies ? $this->companiesBenchmark($iterations) : [];
        $usersCacheResult = $isUsers ? $this->usersBenchmark($iterations, $user) : [];
        $projectsCacheResult = $isProjects ? $this->projectsBenchmark($iterations, $user) : [];

        $results = array_merge($companiesCacheResult, $usersCacheResult, $projectsCacheResult);

        foreach ($results as $name => $time) {
            $this->line("$name: " . number_format($time, 4) . " мс");
        }

        return self::SUCCESS;
    }

    private function companiesBenchmark(int $iterations): array
    {
        $companyRepo = $this->companyRepo;
        $companiesCacheResult = Benchmark::measure([
            'Замер кэширования компаний. Без кэша (холодный запрос)' => function () use ($companyRepo) {
                Cache::tags(['companies'])->flush();
                return $companyRepo->getCompaniesData(1, 50);
            },
            'Замер кэширования компаний. С кэшем (тёплый запрос)' => function () use ($companyRepo) {
                return $companyRepo->getCompaniesData(1, 50);
            },
        ], $iterations);
        Cache::tags(['companies'])->flush();
        return $companiesCacheResult;
    }

    private function usersBenchmark(int $iterations, User $user): array
    {
        $userRepo = $this->userRepo;
        $usersCacheResult = Benchmark::measure([
            'Замер кэширования пользователей. Без кэша (холодный запрос)' => function () use ($user, $userRepo) {
                Cache::tags(['users'])->flush();
                return $userRepo->getUsersDataByCompanyId($user, 1, 50);
            },
            'Замер кэширования пользователей. С кэшем (тёплый запрос)' => function () use ($user, $userRepo) {
                return $userRepo->getUsersDataByCompanyId($user, 1, 50);
            },
        ], $iterations);
        Cache::tags(['users'])->flush();
        return $usersCacheResult;
    }

    private function projectsBenchmark(int $iterations, User $user): array
    {
        $projectRepo = $this->projectRepo;
        $projectsCacheResult = Benchmark::measure([
            'Замер кэширования проектов. Без кэша (холодный запрос)' => function () use ($user, $projectRepo) {
                Cache::tags(['projects'])->flush();
                return $projectRepo->getProjectsDataByUserId($user->id, 1, 50);
            },
            'Замер кэширования проектов. С кэшем (тёплый запрос)' => function () use ($user, $projectRepo) {
                return $projectRepo->getProjectsDataByUserId($user->id, 1, 50);
            },
        ], $iterations);
        Cache::tags(['projects'])->flush();
        return $projectsCacheResult;
    }
}
