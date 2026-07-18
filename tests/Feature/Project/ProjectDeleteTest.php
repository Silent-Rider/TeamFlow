<?php

namespace Tests\Feature\Project;

use App\Enums\ProjectRole;
use App\Models\Company;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_delete_project(): void
    {
        $owner = $this->createUserWithCompany();
        $project = $this->createProject($owner);

        $response = $this->actingAs($owner)->delete(route('projects.destroy', $project));

        $response->assertRedirect();
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_member_cannot_delete_project(): void
    {
        $owner = $this->createUserWithCompany();
        $member = $this->createUserWithCompany();
        $project = $this->createProject($owner);

        $project->users()->attach($member->id, ['role' => ProjectRole::MEMBER]);

        $response = $this->actingAs($member)->delete(route('projects.destroy', $project));

        $response->assertForbidden();
        $this->assertDatabaseHas('projects', ['id' => $project->id]);
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
