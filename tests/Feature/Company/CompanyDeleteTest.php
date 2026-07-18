<?php

namespace Tests\Feature\Company;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_company(): void
    {
        $admin = $this->createAdmin();
        $company = Company::factory()->create();

        $response = $this->actingAs($admin)->delete(route('companies.destroy', $company));

        $response->assertRedirect();
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }

    public function test_regular_user_cannot_delete_company(): void
    {
        $user = $this->createUser();
        $company = Company::factory()->create();

        $response = $this->actingAs($user)->delete(route('companies.destroy', $company));

        $response->assertForbidden();
        $this->assertDatabaseHas('companies', ['id' => $company->id]);
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
