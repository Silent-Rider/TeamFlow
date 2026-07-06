<?php

namespace App\Services;

use App\Models\Company;
use App\Repositories\CompanyRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

readonly class CompanyService
{
    public function __construct(public CompanyRepository $companyRepository)
    {}
    public function getCompaniesData(int $page, int $perPage): array
    {
        $companiesData = $this->companyRepository->getCompaniesData($page, $perPage);
        $companiesData['items'] = $companiesData['items']
            ? Company::hydrate($companiesData['items'])
            : Collection::empty();
        return $companiesData;
    }

    public function create(array $data): void
    {
        if (isset($data['logo'])) {
            $data['logo'] = $data['logo']->store('company_logos', 'public');
        }
        Company::create($data);
        Cache::tags(['companies'])->flush();
    }

    public function update(Company $company, array $data): void
    {
        if (isset($data['logo'])) {
            $this->deleteCompanyLogo($company);
            $data['logo'] = $data['logo']->store('company_logos', 'public');
        }

        $company->update($data);
        Cache::tags(['companies'])->flush();
    }

    public function delete(Company $company): void
    {
        $this->deleteCompanyLogo($company);
        $company->delete();
        Cache::tags(['companies'])->flush();
    }

    private function deleteCompanyLogo(Company $company): void
    {
        if ($company->logo && Storage::disk('public')->exists($company->logo)) {
            Storage::disk('public')->delete($company->logo);
        }
    }
}
