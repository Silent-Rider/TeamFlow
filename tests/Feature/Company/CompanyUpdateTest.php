<?php

namespace Tests\Feature\Company;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_company(): void
    {
        $admin = $this->createAdmin();
        $company = Company::factory()->create();

        $response = $this->actingAs($admin)->put(route('companies.update', $company), [
            'name' => 'Обновленное название',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('companies', ['id' => $company->id, 'name' => 'Обновленное название']);
    }

    public function test_regular_user_cannot_update_company(): void
    {
        $user = $this->createUser();
        $company = Company::factory()->create();

        $response = $this->actingAs($user)->put(route('companies.update', $company), [
            'name' => 'Попытка взлома',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('companies', ['name' => 'Попытка взлома']);
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
