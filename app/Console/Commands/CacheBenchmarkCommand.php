<?php

namespace App\Console\Commands;

use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\Cache;

#[Signature('cache:benchmark')]
#[Description('Measure performance with and without cache')]
class CacheBenchmarkCommand extends Command
{
    public function __construct(
        private readonly UserRepository    $userRepo,
        private readonly ProjectRepository $projectRepo,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $userRepo = $this->userRepo;
        $projectRepo = $this->projectRepo;

        $usersCacheResult = Benchmark::measure([
            'Замер кэширования пользователей. Без кэша (холодный запрос)' => function () use ($userRepo) {
                Cache::tags(['users'])->flush();
                return $userRepo->getAllUsersData(1, 1, 50);
            },
            'Замер кэширования пользователей. С кэшем (тёплый запрос)' => function () use ($userRepo) {
                return $userRepo->getAllUsersData(1, 1, 50);
            },
        ], 100);
        Cache::tags(['users'])->flush();

        $projectsCacheResult = Benchmark::measure([
            'Замер кэширования проектов. Без кэша (холодный запрос)' => function () use ($projectRepo) {
                Cache::tags(['projects'])->flush();
                return $projectRepo->getProjectsDataByUserId(1, 1, 50);
            },
            'Замер кэширования проектов. С кэшем (тёплый запрос)' => function () use ($projectRepo) {
                return $projectRepo->getProjectsDataByUserId(1, 1, 50);
            },
        ], 100);
        Cache::tags(['projects'])->flush();

        $results = array_merge($usersCacheResult, $projectsCacheResult);

        foreach ($results as $name => $time) {
            $this->line("{$name}: " . number_format($time, 4) . " мс");
        }

        return self::SUCCESS;
    }
}
