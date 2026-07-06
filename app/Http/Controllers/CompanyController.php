<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\CompanyCreateRequest;
use App\Http\Requests\Company\CompanyIndexRequest;
use App\Http\Requests\Company\CompanyUpdateRequest;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function __construct(readonly CompanyService $companyService)
    {}

    public function index(CompanyIndexRequest $request): View
    {
        $page = $request->getPage();
        $perPage = $request->getPerPage();
        $companiesData = $this->companyService->getCompaniesData($page, $perPage);
        $companies = $request->getPaginator($companiesData['items'], $companiesData['total']);

        return view('admin.companies', compact('companies'));
    }

    public function create(CompanyCreateRequest $request): RedirectResponse
    {
        $this->authorize('create', Company::class);
        $this->companyService->create($request->validated());
        return back()->with('status', 'company-created');
    }

    public function update(CompanyUpdateRequest $request, Company $company): RedirectResponse
    {
        $this->authorize('update', $company);
        $this->companyService->update($company, $request->validated());
        return back()->with('status', 'company-updated');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $this->authorize('delete', $company);
        $this->companyService->delete($company);
        return back()->with('status', 'company-deleted');
    }
}
