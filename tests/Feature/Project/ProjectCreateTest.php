<?php

namespace Tests\Feature\Project;

use App\Enums\ProjectRole;
use App\Models\Company;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_project(): void
    {
        $user = $this->createUserWithCompany();

        $response = $this->actingAs($user)->post(route('projects.create'), [
            'name' => 'Новый проект',
            'description' => 'Описание проекта',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('projects', [
            'name' => 'Новый проект',
            'creator_id' => $user->id,
            'description' => 'Описание проекта'
        ]);

        $project = Project::where('name', 'Новый проект')->first();
        $this->assertTrue(
            $project->users()
                ->where('user_id', $user->id)
                ->wherePivot('role', ProjectRole::OWNER)
                ->exists()
        );
    }

    public function test_project_creation_fails_without_name(): void
    {
        $user = $this->createUserWithCompany();

        $response = $this->actingAs($user)->post(route('projects.create'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_project_creation_fails_with_too_long_description(): void
    {
        $user = $this->createUserWithCompany();

        $response = $this->actingAs($user)->post(route('projects.create'), [
            'name' => 'Valid Name',
            'description' => str()->random(2001),
        ]);

        $response->assertSessionHasErrors(['description']);
    }

    private function createUserWithCompany(): User
    {
        $company = Company::factory()->create();
        /** @var User $user */
        $user = User::factory()->create(['company_id' => $company->id]);
        return $user;
    }
}
