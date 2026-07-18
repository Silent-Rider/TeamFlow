<?php

namespace Tests\Feature\Company;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_company(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('companies.create'), [
            'name' => 'Новая Компания',
            'description' => 'Описание компании',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('companies', [
            'name' => 'Новая Компания',
            'description' => 'Описание компании'
        ]);
    }

    public function test_regular_user_cannot_create_company(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->post(route('companies.create'), [
            'name' => 'Попытка взлома',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('companies', ['name' => 'Попытка взлома']);
    }

    public function test_company_creation_fails_without_name(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('companies.create'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name']);
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
