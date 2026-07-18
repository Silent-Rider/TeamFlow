<?php

namespace Tests\Feature\Company;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_see_companies_list(): void
    {
        $admin = $this->createAdmin();
        Company::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('companies'));

        $response->assertOk();
        $response->assertViewIs('admin.companies');
        $response->assertViewHas('companies', function ($companies) {
            return $companies->count() === 4;
        });
    }

    public function test_regular_user_cannot_access_companies_index(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->get(route('companies'));

        $response->assertForbidden();
    }

    public function test_index_companies_fails_with_invalid_per_page(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('companies', [
            'per_page' => 101,
        ]));

        $response->assertSessionHasErrors(['per_page']);
    }

    private function createAdmin(): User
    {
        $company = Company::factory()->create();
        /** @var User $user */
        $user = User::factory()->admin()->create(['company_id' => $company->id]);
        return $user;
    }

    private function createUser(): User
    {
        $company = Company::factory()->create();
        /** @var User $user */
        $user = User::factory()->create(['company_id' => $company->id]);
        return $user;
    }
}
