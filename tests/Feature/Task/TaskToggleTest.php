<?php

namespace Task;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskToggleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_toggle_task_status()
    {
        $user = User::factory()->create();
        $task = $this->createTask($user);

        $response = $this->actingAs($user)
            ->patch(route('tasks.toggle', $task));

        $response->assertRedirect();

        $this->assertTrue($task->fresh()->is_done);
    }

    public function test_user_cannot_toggle_task_if_they_do_not_have_access()
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();

        $task = $this->createTask($owner);

        $response = $this->actingAs($stranger)
            ->patch(route('tasks.toggle', $task));

        $response->assertForbidden();
        $this->assertFalse($task->fresh()->is_done);
    }

    private function createTask(User $user): Model
    {
        $project = Project::factory()->create(['creator_id' => $user->id]);
        $project->users()->attach($user->id, ['role' => ProjectRole::OWNER]);

        return Task::factory()->create([
            'project_id' => $project->id,
            'assignee_id' => $user->id,
            'is_done' => false
        ]);
    }
}
