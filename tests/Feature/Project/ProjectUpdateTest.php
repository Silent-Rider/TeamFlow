<?php

namespace Project;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_update_project()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['creator_id' => $owner->id]);
        $project->users()->attach($owner->id, ['role' => ProjectRole::OWNER]);

        $response = $this->actingAs($owner)->put(route('projects.update', $project), [
            'name' => 'Обновленное название',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => 'Обновленное название']);
    }

    public function test_member_cannot_update_project()
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $project = Project::factory()->create(['creator_id' => $owner->id]);
        $project->users()->attach($owner->id, ['role' => ProjectRole::OWNER]);
        $project->users()->attach($member->id, ['role' => ProjectRole::MEMBER]);

        $response = $this->actingAs($member)->put(route('projects.update', $project), [
            'name' => 'Попытка взлома',
        ]);

        $response->assertForbidden();
    }
}
