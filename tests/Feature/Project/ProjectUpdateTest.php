<?php

namespace Tests\Feature\Project;

use App\Enums\ProjectRole;
use App\Models\Company;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_update_project(): void
    {
        $owner = $this->createUserWithCompany();
        $project = $this->createProject($owner);

        $response = $this->actingAs($owner)->put(route('projects.update', $project), [
            'name' => 'Обновленное название',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => 'Обновленное название']);
    }

    public function test_member_cannot_update_project(): void
    {
        $owner = $this->createUserWithCompany();
        $member = $this->createUserWithCompany();
        $project = $this->createProject($owner);

        $project->users()->attach($member->id, ['role' => ProjectRole::MEMBER]);

        $response = $this->actingAs($member)->put(route('projects.update', $project), [
            'name' => 'Попытка взлома',
        ]);

        $response->assertForbidden();
    }

    private function createUserWithCompany(): User
    {
        $company = Company::factory()->create();
        /** @var User $user */
        $user = User::factory()->create(['company_id' => $company->id]);
        return $user;
    }

    private function createProject(User $owner): Project
    {
        /** @var Project $project */
        $project = Project::factory()->create(['creator_id' => $owner->id, 'company_id' => $owner->company_id]);
        $project->users()->attach($owner->id, ['role' => ProjectRole::OWNER]);
        return $project;
    }
}
