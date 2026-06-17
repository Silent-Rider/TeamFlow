<?php

namespace Project;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_delete_project()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['creator_id' => $owner->id]);
        $project->users()->attach($owner->id, ['role' => ProjectRole::OWNER]);

        $response = $this->actingAs($owner)->delete(route('projects.destroy', $project));

        $response->assertRedirect();
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_member_cannot_delete_project()
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $project = Project::factory()->create(['creator_id' => $owner->id]);
        $project->users()->attach($owner->id, ['role' => ProjectRole::OWNER]);
        $project->users()->attach($member->id, ['role' => ProjectRole::MEMBER]);

        $response = $this->actingAs($member)->delete(route('projects.destroy', $project));

        $response->assertForbidden();
        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }
}
