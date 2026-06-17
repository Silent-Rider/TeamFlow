<?php

namespace Task;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_their_assigned_tasks()
    {
        $user = User::factory()->create();

        Task::factory()->count(3)->create([
            'assignee_id' => $user->id,
            'is_done' => false,
        ]);

        Task::factory()->create([
            'assignee_id' => User::factory()->create()->id,
        ]);

        $response = $this->actingAs($user)->get(route('tasks'));

        $response->assertOk();
        $response->assertViewIs('tasks');

        $response->assertViewHas('tasks', function ($tasks) {
            return $tasks->count() === 3;
        });
    }

    public function test_user_can_see_project_tasks_if_has_access()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['creator_id' => $owner->id]);
        $project->users()->attach($owner->id, ['role' => ProjectRole::OWNER]);

        Task::factory()->count(2)->create([
            'project_id' => $project->id,
            'assignee_id' => $owner->id,
        ]);

        $response = $this->actingAs($owner)
            ->get(route('tasks', ['project_id' => $project->id]));

        $response->assertOk();
        $response->assertViewHas('tasks', function ($tasks) {
            return $tasks->count() === 2;
        });
    }

    public function test_user_cannot_see_tasks_of_project_without_access()
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();

        $project = Project::factory()->create(['creator_id' => $owner->id]);

        Task::factory()->create([
            'project_id' => $project->id,
            'assignee_id' => $owner->id,
        ]);

        $response = $this->actingAs($stranger)
            ->get(route('tasks', ['project_id' => $project->id]));

        $response->assertForbidden();
    }

    public function test_tasks_are_filtered_by_status_correctly()
    {
        $user = User::factory()->create();

        Task::factory()->create([
            'assignee_id' => $user->id,
            'is_done' => true,
            'name' => 'Done Task'
        ]);

        Task::factory()->create([
            'assignee_id' => $user->id,
            'is_done' => false,
            'name' => 'Active Task'
        ]);

        $response = $this->actingAs($user)->get(route('tasks', ['filter' => 'active']));
        $response->assertViewHas('tasks', function ($tasks) {
            return $tasks->count() === 1 && $tasks->first()->name === 'Active Task';
        });

        $response = $this->actingAs($user)->get(route('tasks', ['filter' => 'done']));
        $response->assertViewHas('tasks', function ($tasks) {
            return $tasks->count() === 1 && $tasks->first()->name === 'Done Task';
        });
    }
}
