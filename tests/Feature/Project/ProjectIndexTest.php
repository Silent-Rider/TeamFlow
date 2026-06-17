<?php

namespace Project;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_his_projects()
    {
        $user = User::factory()->create();

        $project1 = Project::factory()->create(['name' => 'TeamFlow']);
        $project1->users()->attach($user->id, ['role' => ProjectRole::OWNER]);

        $project2 = Project::factory()->create(['name' => 'Another Project']);
        $project2->users()->attach($user->id, ['role' => ProjectRole::MEMBER]);

        Project::factory()->create(['name' => 'Secret Project']);

        $response = $this->actingAs($user)->get(route('projects'));

        $response->assertOk();
        $response->assertViewIs('projects');
        $response->assertViewHas('projects', function ($projects) {
            return $projects->count() === 2;
        });
    }

    public function test_index_projects_fails_with_invalid_per_page()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('projects', [
            'per_page' => 101,
        ]));
        $response->assertSessionHasErrors(['per_page']);
    }
}
