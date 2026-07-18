<?php

namespace Tests\Feature\Project;

use App\Enums\ProjectRole;
use App\Models\Company;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_his_projects(): void
    {
        $user = $this->createUserWithCompany();

        $project1 = Project::factory()->create(['name' => 'TeamFlow', 'company_id' => $user->company_id]);
        $project1->users()->attach($user->id, ['role' => ProjectRole::OWNER]);

        $project2 = Project::factory()->create(['name' => 'Another Project', 'company_id' => $user->company_id]);
        $project2->users()->attach($user->id, ['role' => ProjectRole::MEMBER]);

        Project::factory()->create(['name' => 'Secret Project', 'company_id' => $user->company_id]);

        $response = $this->actingAs($user)->get(route('projects'));

        $response->assertOk();
        $response->assertViewIs('projects');
        $response->assertViewHas('projects', function ($projects) {
            return $projects->count() === 2;
        });
    }

    public function test_index_projects_fails_with_invalid_per_page(): void
    {
        $user = $this->createUserWithCompany();

        $response = $this->actingAs($user)->get(route('projects', [
            'per_page' => 101,
        ]));

        $response->assertSessionHasErrors(['per_page']);
    }

    private function createUserWithCompany(): User
    {
        $company = Company::factory()->create();
        /** @var User $user */
        $user = User::factory()->create(['company_id' => $company->id]);
        return $user;
    }
}
