<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use App\Repositories\ProjectRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ProjectRepository::class, ProjectRepository::class);
    }

    public function boot(): void
    {
        User::observe(UserObserver::class);
    }
}
