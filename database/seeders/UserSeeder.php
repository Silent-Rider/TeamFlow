<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /** @noinspection PhpPossiblePolymorphicInvocationInspection */
    public function run(): void
    {
        $companies = Company::all();

        User::factory()->create(array_merge([
            'name' => 'Silent Rider',
            'email' => 'silent.30.rider.10@gmail.com',
            'role' => UserRole::ADMIN],
            $this->getCompanyAttributes($companies->random())));

        foreach ($companies as $company) {
            User::factory()->count(3)->create($this->getCompanyAttributes($company));
            User::factory()->unverified()->count(1)->create($this->getCompanyAttributes($company));
        }
    }

    private function getCompanyAttributes(Company $company): array
    {
        return [
            'company_id' => $company->id,
            'created_at' => $company->created_at
        ];
    }
}
