<?php

namespace App\Repositories;

use App\Models\Company;
use Illuminate\Support\Facades\Cache;

readonly class CompanyRepository
{
    public function getCompaniesData(int $page, int $perPage): array
    {
        $key = "companies:page:{$page}:per:{$perPage}";
        return Cache::tags(['companies'])->remember($key, config('cache.ttl.companies'), function () use ($page, $perPage) {
            $query = Company::query()->orderBy('created_at', 'desc');
            return [
                'total' => $query->count(),
                'items' => $query->forPage($page, $perPage)->get()->toArray(),
            ];
        });
    }
}
